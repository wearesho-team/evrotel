<?php

namespace Wearesho\Evrotel\AutoDial\Exception;

use Psr;
use Wearesho\Evrotel\AutoDial;
use Wearesho\Evrotel\AutoDial\RequestInterface;

/**
 * Class TimeLimit
 * @package Wearesho\Evrotel\AutoDial\Exception
 */
class TimeLimit extends AutoDial\Exception
{
    public function __construct(
        RequestInterface $request,
        Psr\Http\Message\ResponseInterface $response,
        int $code = 2,
        \Throwable $previous = null
    ) {
        parent::__construct($request, $response, $code, $previous);
    }
}
