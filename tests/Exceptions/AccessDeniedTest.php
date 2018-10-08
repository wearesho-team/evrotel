<?php

namespace Wearesho\Evrotel\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class AccessDeniedTest
 * @package Wearesho\Evrotel\Tests\Exceptions
 */
class AccessDeniedTest extends TestCase
{
    public function testDefaultMessage(): void
    {
        $exception = new Evrotel\Exceptions\AccessDenied();
        $this->assertEquals(
            "Invalid authorization token",
            $exception->getMessage()
        );
    }
}
