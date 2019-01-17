<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Statistics;

/**
 * Trait ConfigTrait
 * @package Wearesho\Evrotel\Statistics
 */
trait ConfigTrait
{
    protected $baseUrl = ConfigInterface::DEFAULT_BASE_URL;

    /** @var string|null */
    protected $autoDialNumber;

    /**
     * @inheritdoc
     * @see ConfigInterface::getBaseUrl()
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getAutoDialNumber(): ?string
    {
        return $this->autoDialNumber;
    }
}
