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
     * @see Disposition
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
            $response = $e->getResponse();
            throw new Exception($request, $response, 0);
        }

        $disposition = trim((string)$response->getBody());
        if (strpos((string)$disposition, "TimeLimit") !== false) {
            throw new Exception\TimeLimit($request, $response, 2);
        }
        $isDisposition = in_array($disposition, [
            Disposition::NO_ANSWER,
            Disposition::ANSWER,
            Disposition::CONGESTION,
            Disposition::BUSY,
            Disposition::BAD,
            Disposition::NONE,
        ], true);

        if (!$isDisposition) {
            throw new Exception\InvalidDisposition($request, $response, 1);
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
