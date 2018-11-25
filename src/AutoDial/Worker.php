<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\AutoDial;

use Psr\Http\Message\ResponseInterface;
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
    public function push(RequestInterface $request): ResponseInterface
    {
        $prefix = $this->config->getBillCode() . '_';
        $mediaFile = $request->getFileName();
        if (mb_substr($mediaFile, 0, mb_strlen($prefix)) !== $prefix) {
            $mediaFile = $prefix . $mediaFile;
        }

        return $this->client->request(
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
