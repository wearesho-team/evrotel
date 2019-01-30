<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Initializer;

use Wearesho\Evrotel;
use GuzzleHttp;

/**
 * Class Client
 * @package Wearesho\Evrotel\Initializer
 */
class Client
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
     * @param string $receiver 380XXXXXXXXX
     * @param array $from internal operator number
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function start(string $receiver, array $from = []): void
    {
        $this->validateReceiver($receiver);
        $this->validateFrom($from);

        $response = $this->client->request(
            'POST',
            \rtrim($this->config->getBaseUrl(), '/') . '/html/phpagi/call_worker_ext_api.php',
            [
                GuzzleHttp\RequestOptions::FORM_PARAMS => [
                    'billcode' => $this->config->getBillCode(),
                    'ext' => \implode(",", $from),
                    'nm' => $receiver,
                ],
                GuzzleHttp\RequestOptions::HEADERS => [
                    'Authorization' => $this->config->getToken(),
                ],
            ]
        );

        $body = (string)$response->getBody();
        if (\preg_match('/^bad$/', $body)) {
            throw new \RuntimeException("Bad response from Evrotel");
        }
    }

    protected function validateReceiver(string $receiver): void
    {
        if (!\preg_match('/^380\d{9}$/', $receiver)) {
            throw new \InvalidArgumentException(
                "Unsupported receiver format, expected 380XXXXXXXXX: " . $receiver
            );
        }
    }

    protected function validateFrom(array &$from): void
    {
        foreach ($from as &$number) {
            if (\is_object($number) && \method_exists($number, '__toString')) {
                $number = (string)$number;
                continue;
            }
            if (\is_string($number)) {
                continue;
            }
            throw new \InvalidArgumentException(
                "Invalid from, expected string or string-convertible object: "
                . \var_export($number, true)
            );
        }
    }
}
