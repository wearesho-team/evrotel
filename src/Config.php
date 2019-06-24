<?php

declare(strict_types=1);

namespace Wearesho\Evrotel;

/**
 * Class Config
 * @package Wearesho\Evrotel
 */
class Config implements ConfigInterface
{
    use ConfigTrait;

    public function __construct(
        string $token,
        int $billCode,
        string $baseUrl = ConfigInterface::DEFAULT_BASE_URL,
        string $autoDialUrl = ConfigInterface::DEFAULT_AUTO_DIAL_URL
    ) {
        $this->token = $token;
        $this->billCode = $billCode;
        $this->baseUrl = $baseUrl;
        $this->autoDialUrl = $autoDialUrl;
    }
}
