<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\AutoDial;

/**
 * Interface RequestInterface
 * @package Wearesho\Evrotel\AutoDial
 */
interface RequestInterface
{
    public function getFileName(): string;

    public function getPhone(): string;
}
