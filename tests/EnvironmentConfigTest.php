<?php

namespace Wearesho\Evrotel\Tests;

use Horat1us\Environment\MissingEnvironmentException;
use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class EnvironmentConfigTest
 * @package Wearesho\Evrotel\Tests
 */
class EnvironmentConfigTest extends TestCase
{
    protected const TEST_TOKEN = 'testEnvironmentToken';

    /** @var Evrotel\EnvironmentConfig */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Evrotel\EnvironmentConfig();
    }

    public function testGetToken(): void
    {
        putenv('EVROTEL_TOKEN=' . static::TEST_TOKEN);
        $token = $this->config->getToken();
        $this->assertEquals(static::TEST_TOKEN, $token);

        $this->expectException(MissingEnvironmentException::class);
        putenv('EVROTEL_TOKEN');
        $this->config->getToken();
    }
}
