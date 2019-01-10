<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Tests\AutoDial;

use GuzzleHttp;
use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class WorkerTest
 * @package Wearesho\Evrotel\Tests\AutoDial
 */
class WorkerTest extends TestCase
{
    protected const TOKEN = 'testToken';
    protected const BILL_CODE = 6667;
    protected const BASE_URL = 'https://test.dev/';

    /** @var GuzzleHttp\Handler\MockHandler */
    protected $mock;

    /** @var array */
    protected $container;

    /** @var Evrotel\AutoDial\Worker */
    protected $worker;

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

        $this->worker = new Evrotel\AutoDial\Worker($config, $client);
    }

    public function testRequest(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response()
        );

        $phone = '380000000000';
        $fileName = 'file.wav';

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->worker->push(new Evrotel\AutoDial\Request($phone, $fileName));

        $this->assertCount(1, $this->container);

        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(static::TOKEN, $request->getHeaderLine('Authorization'));

        $uri = (string)$request->getUri();
        $this->assertEquals('https://test.dev/html/phpagi/call_worker_api.php', $uri);

        $body = (string)$request->getBody();
        $this->assertEquals('billcode=6667&mediafile=6667_file.wav&nm=380000000000', $body);
    }

    public function testFullUrlMediaFileRequest(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response()
        );

        $phone = '380000000000';
        $fileName = 'https://wearesho.com/dir/file.wav';

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->worker->push(new Evrotel\AutoDial\Request($phone, $fileName));

        $this->assertCount(1, $this->container);

        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];

        $body = (string)$request->getBody();
        $this->assertEquals('billcode=6667&mediafile=6667_file.wav&nm=380000000000', $body);
    }
}
