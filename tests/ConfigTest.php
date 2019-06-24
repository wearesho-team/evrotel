<?php

namespace Wearesho\Evrotel\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class ConfigTest
 * @package Wearesho\Evrotel\Tests
 */
class ConfigTest extends TestCase
{
    protected const TEST_TOKEN = 'testToken';
    protected const TEST_BILL_CODE = 6667;
    protected const TEST_BASE_URL = 'https://test.dev/';
    protected const TEST_AUTO_DIAL_URL = 'https://test.dev/call_worker_api.php';

    /** @var Evrotel\Config */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Evrotel\Config(
            static::TEST_TOKEN,
            static::TEST_BILL_CODE,
            static::TEST_BASE_URL,
            static::TEST_AUTO_DIAL_URL
        );
    }

    public function testGetToken(): void
    {
        $token = $this->config->getToken();
        $this->assertEquals(static::TEST_TOKEN, $token);
    }

    public function testGetBillCode(): void
    {
        $billCode = $this->config->getBillCode();
        $this->assertEquals(static::TEST_BILL_CODE, $billCode);
    }

    public function testGetBaseUrl(): void
    {
        $baseUrl = $this->config->getBaseUrl();
        $this->assertEquals(static::TEST_BASE_URL, $baseUrl);
    }

    public function testDefaultBaseUrl(): void
    {
        $config = new Evrotel\Config(static::TEST_TOKEN, static::TEST_BILL_CODE);
        $baseUrl = $config->getBaseUrl();
        $this->assertEquals(Evrotel\ConfigInterface::DEFAULT_BASE_URL, $baseUrl);
    }

    public function testGetAutoDialUrl(): void
    {
        $autoDialUrl = $this->config->getAutoDialUrl();
        $this->assertEquals(static::TEST_AUTO_DIAL_URL, $autoDialUrl);
    }
}
