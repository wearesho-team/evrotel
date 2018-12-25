<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Tests\Statistics\Call;

use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class CollectionTest
 * @package Wearesho\Evrotel\Tests\Statistics\Call
 */
class CollectionTest extends TestCase
{
    public function testType(): void
    {
        $collection = new Evrotel\Statistics\Call\Collection();
        $this->assertEquals(
            Evrotel\Statistics\Call::class,
            $collection->type()
        );
    }
}
