<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Tests\Initializer;

use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;
use GuzzleHttp;

/**
 * Class ClientTest
 * @package Wearesho\Evrotel\Tests\Initializer
 */
class ClientTest extends TestCase
{
    protected const TOKEN = 'testToken';
    protected const BILL_CODE = 6667;
    protected const BASE_URL = 'https://test.dev/';

    protected const FROM = ['1',];
    protected const RECEIVER = '380970000000';

    /** @var Evrotel\ConfigInterface */
    protected $config;

    /** @var GuzzleHttp\Handler\MockHandler */
    protected $mock;

    /** @var array */
    protected $container;

    /** @var Evrotel\Initializer\Client */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new GuzzleHttp\Handler\MockHandler();
        $this->container = [];
        $history = GuzzleHttp\Middleware::history($this->container);
        $stack = new GuzzleHttp\HandlerStack($this->mock);
        $stack->push($history);

        $client = new GuzzleHttp\Client(['handler' => $stack,]);
        $this->config = new Evrotel\Config(static::TOKEN, static::BILL_CODE, static::BASE_URL);

        $this->client = new Evrotel\Initializer\Client($this->config, $client);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported receiver format, expected 380XXXXXXXXX: 390000000000
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testInvalidReceiver(): void
    {
        $this->client->start('390000000000');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid from, expected string or string-convertible object: 4
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testInvalidFrom(): void
    {
        $this->client->start(static::RECEIVER, [0x4]);
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testRequestMethod(): void
    {
        $this->mock->append(new GuzzleHttp\Psr7\Response(200, []));
        $this->client->start(static::RECEIVER, static::FROM);
        $this->assertCount(1, $this->container);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Request::class, $this->container[0]['request']);
        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];
        $this->assertEquals('POST', $request->getMethod());
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testRequestUri(): void
    {
        $this->mock->append(new GuzzleHttp\Psr7\Response(200, []));
        $this->client->start(static::RECEIVER, static::FROM);
        $this->assertCount(1, $this->container);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Request::class, $this->container[0]['request']);
        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];
        $this->assertEquals(
            static::BASE_URL . 'html/phpagi/call_worker_ext_api.php',
            (string)$request->getUri()
        );
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testRequestBody(): void
    {
        $this->mock->append(new GuzzleHttp\Psr7\Response(200, []));
        $from = [
            '101',
            new class
            {
                public function __toString(): string
                {
                    return '102';
                }
            },
        ];
        $this->client->start(static::RECEIVER, $from);
        $this->assertCount(1, $this->container);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Request::class, $this->container[0]['request']);
        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];

        $body = (string)$request->getBody();
        $this->assertEquals(
            'billcode=6667&ext=101%2C102&nm=380970000000',
            $body
        );

    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testAuthorization(): void
    {
        $this->mock->append(new GuzzleHttp\Psr7\Response(200, []));
        $this->client->start(static::RECEIVER, static::FROM);
        $this->assertCount(1, $this->container);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Request::class, $this->container[0]['request']);
        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];

        $headers = $request->getHeader('Authorization');
        $this->assertCount(1, $headers);
        $this->assertEquals($this->config->getToken(), $headers[0]);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Bad response from Evrotel
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testBadResponse(): void
    {
        $this->mock->append(new GuzzleHttp\Psr7\Response(200, [], 'bad'));
        $this->client->start(static::RECEIVER, static::FROM);
    }
}
