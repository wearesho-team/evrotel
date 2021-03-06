<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Tests\Statistics;

use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class ConfigTest
 * @package Wearesho\Evrotel\Tests\Statistics
 */
class ConfigTest extends TestCase
{
    protected const BASE_URL = 'https://wearesho.com/';
    protected const AUTODIAL_NUMBER = '001';

    /** @var Evrotel\Statistics\Config */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Evrotel\Statistics\Config(static::BASE_URL, static::AUTODIAL_NUMBER);
    }

    public function testGetBaseUrl(): void
    {
        $this->assertEquals(
            static::BASE_URL,
            $this->config->getBaseUrl()
        );
    }

    public function getGetAutoDialNumber(): void
    {
        $this->assertEquals(
            static::AUTODIAL_NUMBER,
            $this->config->getAutoDialNumber()
        );
    }
}
