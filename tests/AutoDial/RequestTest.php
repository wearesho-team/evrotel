<?php

namespace Wearesho\Evrotel\Tests\AutoDial;

use PHPUnit\Framework\TestCase;
use Wearesho\Evrotel;

/**
 * Class RequestTest
 * @package Wearesho\Evrotel\Tests\AutoDial
 */
class RequestTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid ukranian phone format: 380
     */
    public function testInvalidPhone(): void
    {
        new Evrotel\AutoDial\Request('380', 'filename.wav');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage File name filename.mp3 does not have .wav extension
     */
    public function testInvalidFileName(): void
    {
        new Evrotel\AutoDial\Request('380000000000', 'filename.mp3');
    }

    public function testGets(): void
    {
        $phone = '380000000000';
        $fileName = 'filename.wav';

        $request = new Evrotel\AutoDial\Request($phone, $fileName);

        $this->assertEquals($phone, $request->getPhone());
        $this->assertEquals($fileName, $request->getFileName());
    }
}
