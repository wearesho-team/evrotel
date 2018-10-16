<?php

declare(strict_types=1);

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
        return (string)$this->getEnv('TOKEN');
    }

    public function getBillCode(): int
    {
        return (int)$this->getEnv('BILL_CODE');
    }
}
