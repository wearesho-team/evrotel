<?php

namespace Wearesho\Evrotel\AutoDial;

/**
 * Interface Disposition
 * @package Wearesho\Evrotel\AutoDial
 */
interface Disposition
{
    public const ANSWER = 'ANSWER';
    public const BAD = 'BAD';
    public const BUSY = 'BUSY';
    public const NO_ANSWER = 'NOANSWER';
    public const CONGESTION = 'CONGESTION';
    public const GOOD = 'GOOD';
    public const NONE = 'none';
}
