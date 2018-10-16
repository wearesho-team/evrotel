<?php

declare(strict_types=1);

namespace Wearesho\Evrotel;

/**
 * Interface ConfigInterface
 * @package Wearesho\Evrotel
 */
interface ConfigInterface
{
    public function getBillCode(): int;

    public function getToken(): string;
}
