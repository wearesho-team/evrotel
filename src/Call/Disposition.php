<?php

namespace Wearesho\Evrotel\Call;

/**
 * Interface Disposition
 * @package Wearesho\Evrotel\Call
 */
interface Disposition
{
    public const ANSWERED = 'ANSWERED';
    public const BUSY = 'BUSY';
    public const CONGESTION = 'CONGESTION';
    public const FAILED = 'FAILED';
    public const NO_ANSWER = 'NO ANSWER';
}
