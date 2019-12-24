<?php

namespace Wearesho\Evrotel\Receiver\Request;

use Wearesho\Evrotel;

/**
 * Class End
 * @package Wearesho\Evrotel\Receiver\Request
 */
class End extends Evrotel\Receiver\Request
{
    /**
     * @see Evrotel\Call\Disposition
     * @var string
     */
    protected $disposition;

    /** @var int */
    protected $id;

    /** @var \DateInterval */
    protected $duration;

    /** @var string|null */
    protected $recordFileUrl;

    public function __construct(
        string $direction,
        \DateTime $date,
        string $disposition,
        int $id,
        \DateInterval $duration,
        ?string $recordFileUrl
    ) {
        parent::__construct($direction, $date);
        $this->id = $id;
        $this->date = $date;
        $this->duration = $duration;

        $this->validateDisposition($disposition);
        $this->disposition = $disposition;

        $this->recordFileUrl = $recordFileUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return Evrotel\Call\Status::END;
    }

    public function getDisposition(): string
    {
        return $this->disposition;
    }

    public function getRecordFileUrl(): ?string
    {
        return $this->recordFileUrl;
    }

    public function getDuration(): \DateInterval
    {
        return $this->duration;
    }

    private function validateDisposition(string $disposition): void
    {
        $dispositions = [
            Evrotel\Call\Disposition::ANSWERED => 0,
            Evrotel\Call\Disposition::BUSY => 0,
            Evrotel\Call\Disposition::CONGESTION => 0,
            Evrotel\Call\Disposition::FAILED => 0,
            Evrotel\Call\Disposition::NO_ANSWER => 0,
        ];

        if (!isset($dispositions[$disposition])) {
            throw new \InvalidArgumentException("Disposition is invalid");
        }
    }
}
