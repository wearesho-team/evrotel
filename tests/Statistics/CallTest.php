<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Tests\Statistics;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class CallTest
 * @package Wearesho\Evrotel\Tests\Statistics
 */
class CallTest extends TestCase
{
    protected const DURATION = 10;
    protected const CHANNEL = 'Local/380971642002@out_vpbx_007-0000e57d;2';
    protected const FILE = 'https://wearesho.com/autodial.wav';
    protected const ID = 1;
    protected const IS_AUTO = true;
    protected const DATE = '2018-12-01 12:00:00';
    protected const TO = '380980000000';
    protected const DIRECTION = Evrotel\Call\Direction::OUTCOME;
    protected const FROM = '6667';
    protected const DISPOSITION = Evrotel\Call\Disposition::ANSWERED;

    /** @var Evrotel\Statistics\Call */
    protected $call;

    protected function setUp(): void
    {
        parent::setUp();
        $this->call = new Evrotel\Statistics\Call(
            static::CHANNEL,
            static::ID,
            Carbon::parse(static::DATE),
            static::DURATION,
            static::DIRECTION,
            static::FROM,
            static::TO,
            static::DISPOSITION,
            static::FILE,
            static::IS_AUTO
        );
    }

    public function testGetDuration(): void
    {
        $this->assertEquals(
            static::DURATION,
            $this->call->getDuration()
        );
    }

    public function testGetChannel(): void
    {
        $this->assertEquals(
            static::CHANNEL,
            $this->call->getChannel()
        );
    }

    public function testGetFile(): void
    {
        $this->assertEquals(
            static::FILE,
            $this->call->getFile()
        );
    }

    public function testGetId(): void
    {
        $this->assertEquals(
            static::ID,
            $this->call->getId()
        );
    }

    public function testIsAuto(): void
    {
        $this->assertEquals(
            static::IS_AUTO,
            $this->call->isAuto()
        );
    }

    public function testGetDate(): void
    {
        $this->assertEquals(
            Carbon::parse(static::DATE)->toDateTimeString(),
            $this->call->getDate()->format('Y-m-d H:i:s')
        );
    }

    public function testGetTo(): void
    {
        $this->assertEquals(
            static::TO,
            $this->call->getTo()
        );
    }

    public function testGetDirection(): void
    {
        $this->assertEquals(
            static::DIRECTION,
            $this->call->getDirection()
        );
    }

    public function testGetFrom(): void
    {
        $this->assertEquals(
            static::FROM,
            $this->call->getFrom()
        );
    }

    public function testGetDisposition(): void
    {
        $this->assertEquals(
            static::DISPOSITION,
            $this->call->getDisposition()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid disposition: invalid
     */
    public function testInvalidDisposition(): void
    {
        $call = new Evrotel\Statistics\Call(
            static::CHANNEL,
            static::ID,
            Carbon::parse(static::DATE),
            static::DURATION,
            static::DIRECTION,
            static::FROM,
            static::TO,
            'invalid',
            static::FILE,
            static::IS_AUTO
        );
    }


    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid direction: d
     */
    public function testInvalidDirection(): void
    {
        $call = new Evrotel\Statistics\Call(
            static::CHANNEL,
            static::ID,
            Carbon::parse(static::DATE),
            static::DURATION,
            'd',
            static::FROM,
            static::TO,
            static::DISPOSITION,
            static::FILE,
            static::IS_AUTO
        );
    }
}
