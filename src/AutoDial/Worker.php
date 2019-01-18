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
     * @return string one of dispositions constants
     * @see Evrotel\Call\Disposition
     *
     * @throws Exception
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function push(RequestInterface $request): string
    {
        $mediaFile = $this->filterFileName($request->getFileName());

        try {
            $response = $this->client->request(
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
        } catch (GuzzleHttp\Exception\RequestException $e) {
            throw new Exception($request, $e->getResponse(), 0);
        }

        $disposition = trim((string)$response->getBody());
        $isDisposition = in_array($disposition, [
            Evrotel\Call\Disposition::NO_ANSWER,
            Evrotel\Call\Disposition::ANSWERED,
            Evrotel\Call\Disposition::CONGESTION,
            Evrotel\Call\Disposition::BUSY,
            Evrotel\Call\Disposition::FAILED,
        ]);

        if (!$isDisposition) {
            throw new Exception($request, $response, 1);
        }

        return $disposition;
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
