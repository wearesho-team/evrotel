<?php

namespace Wearesho\Evrotel\Exceptions;

use Throwable;

/**
 * Class AccessDenied
 * @package Wearesho\Evrotel\Exceptions
 */
class AccessDenied extends \Exception
{
    public function __construct(
        string $message = "Invalid authorization token",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
