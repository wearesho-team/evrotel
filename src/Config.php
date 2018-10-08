<?php

namespace Wearesho\Evrotel;

/**
 * Class Config
 * @package Wearesho\Evrotel
 */
class Config implements ConfigInterface
{
    use ConfigTrait;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
