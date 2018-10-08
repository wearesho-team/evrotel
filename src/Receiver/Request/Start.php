<?php

namespace Wearesho\Evrotel\Receiver\Request;

use Wearesho\Evrotel;

/**
 * Class Start
 * @package Wearesho\Evrotel\Receiver\Request
 */
class Start extends Evrotel\Receiver\Request
{
    /** @var string */
    protected $from;

    /** @var string */
    protected $to;

    public function __construct(string $direction, \DateTime $date, string $from, string $to)
    {
        parent::__construct($direction, $date);

        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @see Evrotel\Call\Status
     * @return string
     */
    public function getStatus(): string
    {
        return Evrotel\Call\Status::START;
    }
}
