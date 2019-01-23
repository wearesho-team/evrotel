<?php

namespace Wearesho\Evrotel\AutoDial\Exception;

use Psr;
use Wearesho\Evrotel\AutoDial;
use Wearesho\Evrotel\AutoDial\RequestInterface;

/**
 * Class InvalidDisposition
 * @package Wearesho\Evrotel\AutoDial\Exception
 */
class InvalidDisposition extends AutoDial\Exception
{
    public function __construct(
        RequestInterface $request,
        Psr\Http\Message\ResponseInterface $response,
        int $code = 1,
        \Throwable $previous = null
    ) {
        parent::__construct($request, $response, $code, $previous);
    }
}
