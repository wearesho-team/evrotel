<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Statistics;

/**
 * Interface ConfigInterface
 * @package Wearesho\Evrotel\Statistics
 */
interface ConfigInterface
{
    public const DEFAULT_BASE_URL = 'https://callme.sipiko.net/';

    /**
     * Base URL for statistics requests
     * @return string
     */
    public function getBaseUrl(): string;
}
