<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Statistics;

use Horat1us\Environment;

/**
 * Class EnvironmentConfig
 * @package Wearesho\Evrotel\Statistics
 */
class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    public function __construct(string $keyPrefix = 'EVROTEL_STATISTICS_')
    {
        parent::__construct($keyPrefix);
    }

    /**
     * @inheritdoc
     */
    public function getBaseUrl(): string
    {
        return $this->getEnv('BASE_URL', ConfigInterface::DEFAULT_BASE_URL);
    }
}
