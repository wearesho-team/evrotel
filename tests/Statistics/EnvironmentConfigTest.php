<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Tests\Statistics;

use Wearesho\Evrotel;
use PHPUnit\Framework\TestCase;

/**
 * Class EnvironmentConfigTest
 * @package Wearesho\Evrotel\Tests\Statistics
 */
class EnvironmentConfigTest extends TestCase
{
    protected const PREFIX = 'ESECTESTPREFIX_';

    /** @var Evrotel\Statistics\EnvironmentConfig */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Evrotel\Statistics\EnvironmentConfig(static::PREFIX);
    }

    public function testDefaultBaseUrl(): void
    {
        putenv(static::PREFIX . 'BASE_URL');
        $this->assertEquals(
            Evrotel\Statistics\ConfigInterface::DEFAULT_BASE_URL,
            $this->config->getBaseUrl()
        );
    }

    public function testBaseUrl(): void
    {
        $url = 'http://wearesho.com/';
        putenv(static::PREFIX . 'BASE_URL=' . $url);
        $this->assertEquals(
            $url,
            $this->config->getBaseUrl()
        );
        putenv(static::PREFIX . 'BASE_URL');
    }

    public function testNullAutoDialNumber(): void
    {
        putenv(static::PREFIX . 'AUTODIAL_NUMBER');
        $this->assertNull($this->config->getAutoDialNumber());
    }

    public function tesAutoDialNumber(): void
    {
        putenv(static::PREFIX . 'AUTODIAL_NUMBER=001');
        $this->assertEquals(
            '001',
            $this->config->getAutoDialNumber()
        );
    }
}
