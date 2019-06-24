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
    public const DEFAULT_AUTO_DIAL_URL = 'https://autodial.evro-tel.com.ua/autodialapi/call_worker_api.php';

    public function getBillCode(): int;

    public function getToken(): string;

    public function getBaseUrl(): string;

    public function getAutoDialUrl(): string;
}
