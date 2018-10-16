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

    public function getBillCode(): int
    {
        return (int)$this->billCode;
    }

    public function getToken(): string
    {
        return (string)$this->token;
    }
}
