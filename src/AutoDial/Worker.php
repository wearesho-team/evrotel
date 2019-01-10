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
     * @return ResponseInterface
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function push(RequestInterface $request): ResponseInterface
    {
        $mediaFile = $this->filterFileName($request->getFileName());

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

    protected function filterFileName(string $fileName): string
    {
        if (filter_var($fileName, FILTER_VALIDATE_URL) !== false) {
            $fileName = basename(parse_url($fileName, PHP_URL_PATH));
        }

        $prefix = $this->config->getBillCode() . '_';
        if (mb_substr($fileName, 0, mb_strlen($prefix)) !== $prefix) {
            $fileName = $prefix . $fileName;
        }

        return $fileName;
    }
}
