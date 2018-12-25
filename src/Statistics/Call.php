<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Statistics;

use Wearesho\Evrotel;

/**
 * Class Call
 * @package Wearesho\Evrotel\Statistics
 */
class Call
{
    /** @var string */
    protected $channel;

    /** @var int */
    protected $id;

    /** @var \DateTimeInterface */
    protected $date;

    /** @var int */
    protected $duration;

    /**
     * @var string
     * @see Evrotel\Call\Direction
     */
    protected $direction;

    /** @var string */
    protected $from;

    /** @var string */
    protected $to;

    /**
     * @var string
     * @see Evrotel\Call\Disposition
     */
    protected $disposition;

    /** @var string */
    protected $file;

    /** @var bool */
    protected $auto;

    public function __construct(
        string $channel,
        int $id,
        \DateTimeInterface $date,
        int $duration,
        string $direction,
        string $from,
        string $to,
        string $disposition,
        string $file,
        bool $auto
    ) {
        $this->channel = $channel;
        $this->id = $id;
        $this->date = $date;
        $this->duration = $duration;
        $this->validateDirection($direction);
        $this->direction = $direction;
        $this->from = $from;
        $this->to = $to;
        $this->validateDisposition($disposition);
        $this->disposition = $disposition;
        $this->file = $file;
        $this->auto = $auto;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getDisposition(): string
    {
        return $this->disposition;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return bool
     */
    public function isAuto(): bool
    {
        return $this->auto;
    }

    protected function validateDirection(string $direction): void
    {
        $isValid = in_array($direction, [
            Evrotel\Call\Direction::OUTCOME,
            Evrotel\Call\Direction::INCOME,
        ]);
        if (!$isValid) {
            throw new \InvalidArgumentException("Invalid direction: $direction");
        }
    }

    protected function validateDisposition(string $disposition): void
    {
        $isValid = in_array($disposition, [
            Evrotel\Call\Disposition::ANSWERED,
            Evrotel\Call\Disposition::FAILED,
            Evrotel\Call\Disposition::BUSY,
            Evrotel\Call\Disposition::NO_ANSWER,
            Evrotel\Call\Disposition::CONGESTION,
        ]);
        if (!$isValid) {
            throw new \InvalidArgumentException("Invalid disposition: {$disposition}");
        }
    }
}
