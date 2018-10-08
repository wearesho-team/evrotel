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

    /** @var Evrotel\Config */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = new Evrotel\Config(static::TEST_TOKEN);
    }

    public function testGetToken(): void
    {
        $token = $this->config->getToken();
        $this->assertEquals(static::TEST_TOKEN, $token);
    }
}
