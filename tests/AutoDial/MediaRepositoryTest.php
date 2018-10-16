<?php

namespace Wearesho\Evrotel\Tests\AutoDial;

use Wearesho\Evrotel;
use GuzzleHttp;
use PHPUnit\Framework\TestCase;

/**
 * Class MediaRepositoryTest
 * @package Wearesho\Evrotel\Tests\AutoDial
 */
class MediaRepositoryTest extends TestCase
{
    protected const TOKEN = 'testToken';
    protected const BILL_CODE = 6667;
    protected const BASE_URL = 'https://test.dev/';

    /** @var GuzzleHttp\Handler\MockHandler */
    protected $mock;

    /** @var array */
    protected $container;

    /** @var Evrotel\AutoDial\MediaRepository */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new GuzzleHttp\Handler\MockHandler();
        $this->container = [];
        $history = GuzzleHttp\Middleware::history($this->container);
        $stack = new GuzzleHttp\HandlerStack($this->mock);
        $stack->push($history);

        $client = new GuzzleHttp\Client(['handler' => $stack,]);
        $config = new Evrotel\Config(static::TOKEN, static::BILL_CODE, static::BASE_URL);

        $this->repository = new Evrotel\AutoDial\MediaRepository($config, $client);
    }

    public function testRequest(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response()
        );

        $link = 'https://api.dev/sound.wav';

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->repository->push($link);

        $this->assertCount(1, $this->container);

        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(static::TOKEN, $request->getHeaderLine('Authorization'));

        $body = (string)$request->getBody();
        $this->assertEquals('billcode=6667&mediafile=https%3A%2F%2Fapi.dev%2Fsound.wav', $body);
    }

    /**
     * Expected behavior: repository should not handle any error
     *
     * @expectedException \GuzzleHttp\Exception\RequestException
     * @expectedExceptionMessage Runtime error
     */
    public function testHandleException(): void
    {
        $this->mock->append(
            new GuzzleHttp\Exception\RequestException(
                'Runtime error',
                new GuzzleHttp\Psr7\Request('GET', 'http://google.com/')
            )
        );
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->repository->push('https://api.dev/sound.wav');
    }
}
