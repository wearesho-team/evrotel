<?php

declare(strict_types=1);

namespace Wearesho\Evrotel;

/**
 * Interface ConfigInterface
 * @package Wearesho\Evrotel
 */
interface ConfigInterface
{
    public const DEFAULT_BASE_URL = 'http://m01.sipiko.net/';

    public function getBillCode(): int;

    public function getToken(): string;

    public function getBaseUrl(): string;
}
