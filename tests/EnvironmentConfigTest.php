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
    protected const TEST_BILL_CODE = 6667;
    protected const TEST_BASE_URL = 'https://test.dev/';
    protected const TEST_AUTO_DIAL_URL = 'https://test.dev/call_worker_api.php';

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

    public function testGetBillCode(): void
    {
        putenv('EVROTEL_BILL_CODE=' . static::TEST_BILL_CODE);
        $billCode = $this->config->getBillCode();
        $this->assertEquals(static::TEST_BILL_CODE, $billCode);

        $this->expectException(MissingEnvironmentException::class);
        putenv('EVROTEL_BILL_CODE');
        $this->config->getBillCode();
    }

    public function testGetBaseUrl(): void
    {
        putenv('EVROTEL_BASE_URL=' . static::TEST_BASE_URL);
        $baseUrl = $this->config->getBaseUrl();
        $this->assertEquals(static::TEST_BASE_URL, $baseUrl);

        putenv('EVROTEL_BASE_URL');
        $defaultBaseUrl = $this->config->getBaseUrl();
        $this->assertEquals(Evrotel\ConfigInterface::DEFAULT_BASE_URL, $defaultBaseUrl);
    }

    public function testGetAutoDialUrl(): void
    {
        putenv('EVROTEL_AUTO_DIAL_URL=' . static::TEST_AUTO_DIAL_URL);
        $autoDialUrl = $this->config->getAutoDialUrl();
        $this->assertEquals(static::TEST_AUTO_DIAL_URL, $autoDialUrl);

        putenv('EVROTEL_AUTO_DIAL_URL');
        $defaultAutoDialUrl = $this->config->getAutoDialUrl();
        $this->assertEquals(Evrotel\ConfigInterface::DEFAULT_AUTO_DIAL_URL, $defaultAutoDialUrl);
    }
}
