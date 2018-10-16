<?php

namespace Wearesho\Evrotel\Tests;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class ReceiverTest
 * @package Wearesho\Evrotel\Tests
 */
class ReceiverTest extends TestCase
{
    /** @var Evrotel\Receiver */
    protected $receiver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->receiver = new Evrotel\Receiver(new Evrotel\Config('testToken', 6667));
        $_SERVER['HTTP_AUTHORIZATION'] = 'testToken';
    }

    /**
     * @expectedException \Wearesho\Evrotel\Exceptions\AccessDenied
     */
    public function testMissingAuthorizationHeader(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = null;
        $this->receiver->getRequest();
    }

    /**
     * @expectedException \Wearesho\Evrotel\Exceptions\AccessDenied
     */
    public function testInvalidAuthorizationHeader(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'invalidToken';
        $this->receiver->getRequest();
    }

    /**
     * @expectedException \Wearesho\Evrotel\Exceptions\BadRequest
     * @expectedExceptionMessage Missing callstatus
     */
    public function testMissingCallStatus(): void
    {
        $this->receiver->getRequest();
    }

    /**
     * @expectedException \Wearesho\Evrotel\Exceptions\BadRequest
     * @expectedExceptionMessage Invalid callstatus
     */
    public function testInvalidCallStatus(): void
    {
        $_POST['callstatus'] = 'invalidCallStatus';
        $this->receiver->getRequest();
    }

    /**
     * @expectedException \Wearesho\Evrotel\Exceptions\BadRequest
     * @expectedExceptionMessage Invalid argument type
     * @expectedExceptionCode 2
     */
    public function testInvalidArgumentsTypeInStart(): void
    {
        $_POST['callstatus'] = Evrotel\Call\Status::START;
        $this->receiver->getRequest();
    }

    /**
     * @expectedException \Wearesho\Evrotel\Exceptions\BadRequest
     * @expectedExceptionMessage Invalid Direction
     * @expectedExceptionCode 1
     */
    public function testInvalidArgumentsInStart(): void
    {
        $_POST['callstatus'] = Evrotel\Call\Status::START;
        $_POST['direction'] = 'invalidDirection';
        $_POST['date'] = Carbon::now()->toDateTimeString();
        $_POST['numberA'] = '';
        $_POST['numberB'] = '';
        $this->receiver->getRequest();
    }

    public function testStart(): void
    {
        $_POST['callstatus'] = Evrotel\Call\Status::START;
        $_POST['direction'] = Evrotel\Call\Direction::INCOME;
        $_POST['date'] = Carbon::now()->toDateTimeString();
        $_POST['numberA'] = '380001234567';
        $_POST['numberB'] = '380007654321';

        /** @var Evrotel\Receiver\Request\Start $request */
        $request = $this->receiver->getRequest();
        $this->assertInstanceOf(Evrotel\Receiver\Request\Start::class, $request);
        $this->assertEquals('380001234567', $request->getFrom());
        $this->assertEquals('380007654321', $request->getTo());
        $this->assertEquals(Evrotel\Call\Status::START, $request->getStatus());
    }

    /**
     * @expectedException \Wearesho\Evrotel\Exceptions\BadRequest
     * @expectedExceptionMessage Invalid argument type
     * @expectedExceptionCode 2
     */
    public function testInvalidArgumentsTypeInEnd(): void
    {
        $_POST['callstatus'] = Evrotel\Call\Status::END;
        $this->receiver->getRequest();
    }

    /**
     * @expectedException \Wearesho\Evrotel\Exceptions\BadRequest
     * @expectedExceptionMessage Disposition is invalid
     * @expectedExceptionCode 1
     */
    public function testInvalidArgumentsInEnd(): void
    {
        $_POST['callstatus'] = Evrotel\Call\Status::END;
        $_POST['direction'] = Evrotel\Call\Direction::INCOME;
        $_POST['date'] = Carbon::now()->toDateTimeString();
        $_POST['billsec'] = 5;
        $_POST['disposition'] = 'invalidDisposition';
        $_POST['recfile'] = 'recfile.mp3';
        $_POST['callid'] = 123;
        $this->receiver->getRequest();
    }

    public function testEnd(): void
    {
        $_POST['callstatus'] = Evrotel\Call\Status::END;
        $_POST['direction'] = Evrotel\Call\Direction::INCOME;
        $_POST['date'] = Carbon::now()->toDateTimeString();
        $_POST['billsec'] = 5;
        $_POST['disposition'] = Evrotel\Call\Disposition::ANSWERED;
        $_POST['recfile'] = 'recfile.mp3';
        $_POST['callid'] = 123;

        /** @var Evrotel\Receiver\Request\End $request */
        $request = $this->receiver->getRequest();
        $this->assertInstanceOf(Evrotel\Receiver\Request\End::class, $request);
        $this->assertEquals(123, $request->getId());
        $this->assertEquals('recfile.mp3', $request->getRecordFileUrl());
        $this->assertEquals(new \DateInterval('PT5S'), $request->getDuration());
        $this->assertEquals(Evrotel\Call\Direction::INCOME, $request->getDirection());
        $this->assertEquals(Evrotel\Call\Disposition::ANSWERED, $request->getDisposition());
        $this->assertEquals($_POST['date'], $request->getDate()->format('Y-m-d H:i:s'));
        $this->assertEquals(Evrotel\Call\Status::END, $request->getStatus());
    }
}
