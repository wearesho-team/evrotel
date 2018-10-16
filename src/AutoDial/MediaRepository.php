<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\AutoDial;

use GuzzleHttp;
use Wearesho\Evrotel;

/**
 * Class MediaRepository
 * @package Wearesho\Evrotel\AutoDial
 */
class MediaRepository
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
     * @param string $link
     * @throws GuzzleHttp\Exception\GuzzleException
     * @todo handle duplications errors (or any others, docs required)
     */
    public function push(string $link): void
    {
        $this->client->request(
            'POST',
            rtrim($this->config->getBaseUrl(), '/') . '/html/phpagi/media/index.php',
            [
                GuzzleHttp\RequestOptions::FORM_PARAMS => [
                    'billcode' => $this->config->getBillCode(),
                    'mediafile' => $link,
                ],
                GuzzleHttp\RequestOptions::HEADERS => [
                    'Authorization' => $this->config->getToken(),
                ],
            ]
        );
    }
}
