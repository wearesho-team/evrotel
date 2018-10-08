<?php

namespace Wearesho\Evrotel;

/**
 * Trait ConfigTrait
 * @package Wearesho\Evrotel
 * @see ConfigInterface
 */
trait ConfigTrait
{
    /** @var string */
    protected $token;

    public function getToken(): string
    {
        return $this->token;
    }
}
