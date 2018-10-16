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
     * @return string saved file name that should be used in
     * @see Worker
     * @throws GuzzleHttp\Exception\GuzzleException
     * @throws Evrotel\Exceptions\AutoDial\PushMedia
     */
    public function push(string $link): string
    {
        $response = $this->client->request(
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

        $body = (string)$response->getBody();
        $match = (bool)preg_match(
            "/saved:\s({$this->config->getBillCode()}_.+\.wav)/",
            trim($body),
            $matches
        );

        if (!$match) {
            throw new Evrotel\Exceptions\AutoDial\PushMedia($body);
        }

        return (string)$matches[1];
    }
}
