<?php

namespace Wearesho\Evrotel\Receiver;

use Wearesho\Evrotel;

/**
 * Class Request
 * @package Wearesho\Evrotel\Receiver
 */
abstract class Request
{
    /** @var \DateTime */
    protected $date;

    /**
     * @see Evrotel\Call\Direction
     * @var string
     */
    protected $direction;

    public function __construct(
        string $direction,
        \DateTime $date
    ) {
        $this->validateDirection($direction);
        $this->direction = $direction;

        $this->date = $date;
    }

    /**
     * @see Evrotel\Call\Status
     * @return string
     */
    abstract public function getStatus(): string;


    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    private function validateDirection(string $direction): void
    {
        $data = [
            Evrotel\Call\Direction::INCOME => 0,
            Evrotel\Call\Direction::OUTCOME => 0,
        ];
        $isDirectionValid = isset($data[$direction]);

        if (!$isDirectionValid) {
            throw new \InvalidArgumentException("Invalid Direction");
        }
    }
}
