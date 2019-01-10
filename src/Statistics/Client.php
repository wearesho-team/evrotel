<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Statistics;

use Carbon\Carbon;
use Wearesho\Evrotel;
use GuzzleHttp;

/**
 * Class Client
 * @package Wearesho\Evrotel\Statistics
 */
class Client
{
    /** @var Evrotel\ConfigInterface */
    protected $baseConfig;

    /** @var ConfigInterface */
    protected $config;

    /** @var GuzzleHttp\ClientInterface */
    protected $client;

    public function __construct(
        Evrotel\ConfigInterface $baseConfig,
        ConfigInterface $config,
        GuzzleHttp\ClientInterface $client
    )
    {
        $this->baseConfig = $baseConfig;
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * @param bool $isAuto
     * @param \DateTimeInterface|null $date
     * @return Call\Collection
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function getCalls(bool $isAuto, \DateTimeInterface $date = null): Call\Collection
    {
        $date = ($date ?? Carbon::now())->format('Y-m-d');
        $billCode = $this->baseConfig->getBillCode();
        $suffix = $isAuto ? "_auto" : "";

        $response = $this->client->request(
            'GET',
            rtrim($this->config->getBaseUrl(), '/') . "/statusers/stat_{$billCode}{$suffix}.php",
            [
                GuzzleHttp\RequestOptions::QUERY => [
                    'billcode' => $billCode,
                    'start' => $date,
                ],
            ]
        );

        $body = json_decode((string)$response->getBody(), true);
        $requiredAttributes = [
            'recfile',
            'calldate',
            'channel',
            'id',
            'billsec',
            'direction',
            'numberA',
            'numberB',
            'disposition',
        ];
        $rawCalls = array_filter($body['calls'], function (array $call) use ($requiredAttributes): bool {
            return count(array_intersect_key(array_flip($requiredAttributes), $call)) === count($requiredAttributes);
        });

        /** @var Call[] $calls */
        $calls = array_map(function (array $raw) use ($isAuto): Call {
            return $this->parseCall($raw, $isAuto);
        }, $rawCalls);

        return new Call\Collection($calls);
    }

    private function parseCall(array $raw, bool $isAuto): Call
    {
        $file = rtrim($this->baseConfig->getBaseUrl(), '/')
            . str_replace('/var/www', '', $raw['recfile']);
        $date = Carbon::parse($raw['calldate']);

        return new Call(
            $raw['channel'],
            $raw['id'],
            $date,
            $raw['billsec'],
            $raw['direction'],
            $raw['numberA'],
            $raw['numberB'],
            $raw['disposition'],
            $file,
            $isAuto
        );
    }
}
