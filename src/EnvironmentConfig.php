<?php

namespace Wearesho\Evrotel;

use Horat1us\Environment;

/**
 * Class EnvironmentConfig
 * @package Wearesho\Evrotel
 */
class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    public function __construct(string $keyPrefix = 'EVROTEL_')
    {
        parent::__construct($keyPrefix);
    }

    public function getToken(): string
    {
        return $this->getEnv('TOKEN');
    }
}
