<?php

namespace Wearesho\Evrotel\Tests\AutoDial;

use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;
use GuzzleHttp;

/**
 * Class ExceptionTest
 * @package Wearesho\Evrotel\Tests\AutoDial
 */
class ExceptionTest extends TestCase
{
    /** @var Evrotel\AutoDial\Exception */
    protected $exception;

    /** @var Evrotel\AutoDial\Request */
    protected $request;

    /** @var GuzzleHttp\Psr7\Response */
    protected $response;

    /** @var int */
    protected $code;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Evrotel\AutoDial\Request("380000000000", "file.wav");
        $this->response = new GuzzleHttp\Psr7\Response();
        $this->code = 0;

        $this->exception = new Evrotel\AutoDial\Exception(
            $this->request,
            $this->response,
            $this->code
        );
    }

    public function testGetResponse(): void
    {
        $this->assertEquals(
            $this->response,
            $this->exception->getResponse()
        );
    }

    public function testGetRequest(): void
    {
        $this->assertEquals(
            $this->request,
            $this->exception->getRequest()
        );
    }

    public function testGetCode(): void
    {
        $this->assertEquals(
            $this->code,
            $this->exception->getCode()
        );
    }
}
