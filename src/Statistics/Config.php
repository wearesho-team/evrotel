<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Statistics;

/**
 * Class Config
 * @package Wearesho\Evrotel\Statistics
 */
class Config implements ConfigInterface
{
    use ConfigTrait;

    public function __construct(string $baseUrl = ConfigInterface::DEFAULT_BASE_URL)
    {
        $this->baseUrl = $baseUrl;
    }
}
