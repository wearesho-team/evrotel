<?php

declare(strict_types=1);

namespace Wearesho\Evrotel;

/**
 * Trait ConfigTrait
 * @package Wearesho\Evrotel
 * @see ConfigInterface
 */
trait ConfigTrait
{
    /** @var int */
    protected $billCode;

    /** @var string */
    protected $token;

    /** @var string */
    protected $baseUrl = ConfigInterface::DEFAULT_BASE_URL;

    protected $autoDialUrl = ConfigInterface::DEFAULT_AUTO_DIAL_URL;

    public function getBillCode(): int
    {
        return (int)$this->billCode;
    }

    public function getToken(): string
    {
        return (string)$this->token;
    }

    public function getBaseUrl(): string
    {
        return (string)$this->baseUrl;
    }

    public function getAutoDialUrl(): string
    {
        return $this->autoDialUrl;
    }
}
