<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Tests\Statistics;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;
use GuzzleHttp;

/**
 * Class ClientTest
 * @package Wearesho\Evrotel\Tests\Statistics
 */
class ClientTest extends TestCase
{
    protected const TOKEN = 'token';
    protected const BILL_CODE = 6667;
    protected const FILE_BASE_URL = 'https://wearesho.com/';
    protected const BASE_URL = 'https://horatius.pro/';

    /** @var GuzzleHttp\Handler\MockHandler */
    protected $mock;

    /** @var array */
    protected $container;

    /** @var Evrotel\Statistics\Client */
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new GuzzleHttp\Handler\MockHandler();
        $this->container = [];
        $history = GuzzleHttp\Middleware::history($this->container);
        $stack = new GuzzleHttp\HandlerStack($this->mock);
        $stack->push($history);
        $body = file_get_contents(dirname(dirname(__DIR__)) . '/data/statistics/stats_response.json');
        $this->mock->append(new GuzzleHttp\Psr7\Response(200, [], $body));

        $client = new GuzzleHttp\Client(['handler' => $stack,]);
        $baseConfig = new Evrotel\Config(static::TOKEN, static::BILL_CODE, static::FILE_BASE_URL);
        $config = new Evrotel\Statistics\Config(static::BASE_URL);

        $this->client = new Evrotel\Statistics\Client($baseConfig, $config, $client);
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testAutoUrl(): void
    {
        Carbon::setTestNow(Carbon::parse('2018-12-25'));

        $this->client->getCalls(true);
        $this->assertCount(1, $this->container);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Request::class, $this->container[0]['request'] ?? null);
        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];
        $this->assertEquals(
            static::BASE_URL . 'statusers/stat_6667_auto.php?billcode=6667&start=2018-12-25',
            (string)$request->getUri()
        );

        Carbon::setTestNow();
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testManualUrl(): void
    {
        $date = Carbon::yesterday();
        $this->client->getCalls(false, $date);
        $this->assertCount(1, $this->container);
        $this->assertInstanceOf(GuzzleHttp\Psr7\Request::class, $this->container[0]['request'] ?? null);
        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];
        $this->assertEquals(
            static::BASE_URL . 'statusers/stat_6667.php?billcode=6667&start=' . $date->toDateString(),
            (string)$request->getUri()
        );
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testAutoCollection(): void
    {
        $calls = $this->client->getCalls(true);
        $this->assertCount(2, $calls);
        /** @var Evrotel\Statistics\Call $call */
        foreach ($calls as $call) {
            $this->assertEquals(true, $call->isAuto());
        }
    }

    /**
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function testParsing(): void
    {
        $calls = $this->client->getCalls(false);
        $this->assertCount(2, $calls);
        $this->assertEquals(
            new Evrotel\Statistics\Call(
                'Local/380971642002@out_vpbx_007-0000e57d;2',
                63705443,
                Carbon::parse("2018-12-25T09:03:03+00:00"),
                0,
                Evrotel\Call\Direction::OUTCOME,
                '001',
                '380971642002',
                Evrotel\Call\Disposition::FAILED,
                static::FILE_BASE_URL . 'html/wav/007001/2018-12-25/1545721383.15474064-1196118992.wav',
                false
            ),
            $calls[0]
        );
        $this->assertEquals(
            new Evrotel\Statistics\Call(
                "Local/380994942964@out_vpbx_007-0000e57e;2",
                63705451,
                Carbon::parse("2018-12-25T09:03:03+00:00"),
                0,
                Evrotel\Call\Direction::OUTCOME,
                '001',
                '380994942964',
                Evrotel\Call\Disposition::FAILED,
                static::FILE_BASE_URL . 'html/wav/101/2018-12-25/1545721383.15474065-1000000.wav',
                false
            ),
            $calls[1]
        );
    }
}
