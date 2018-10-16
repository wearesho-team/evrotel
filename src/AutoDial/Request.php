<?php

namespace Wearesho\Evrotel\AutoDial;

/**
 * Class Request
 * @package Wearesho\Evrotel\AutoDial
 */
class Request implements RequestInterface
{
    use RequestTrait;

    public function __construct(string $phone, string $fileName)
    {
        $this->validatePhone($phone);
        $this->phone = $phone;

        $this->validateFileName($fileName);
        $this->fileName = $fileName;
    }
}
