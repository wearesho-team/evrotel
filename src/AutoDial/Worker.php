<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\AutoDial;

use Wearesho\Evrotel;
use GuzzleHttp;

/**
 * Class Worker
 * @package Wearesho\Evrotel\AutoDial
 */
class Worker
{
    /** @var Evrotel\ConfigInterface */
    protected $config;

    /** @var GuzzleHttp\ClientInterface */
    protected $client;

    public function __construct(Evrotel\ConfigInterface $config, GuzzleHttp\ClientInterface $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * @param RequestInterface $request
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function push(RequestInterface $request): void
    {
        $mediaFile = $this->config->getBillCode() . '_' . $request->getFileName();

        $this->client->request(
            'POST',
            rtrim($this->config->getBaseUrl(), '/') . '/html/phpagi/call_worker_api.php',
            [
                GuzzleHttp\RequestOptions::FORM_PARAMS => [
                    'billcode' => $this->config->getBillCode(),
                    'mediafile' => $mediaFile,
                    'nm' => $request->getPhone(),
                ],
                GuzzleHttp\RequestOptions::HEADERS => [
                    'Authorization' => $this->config->getToken(),
                ],
            ]
        );
    }
}
