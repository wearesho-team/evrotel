<?php

namespace Wearesho\Evrotel\AutoDial;

/**
 * Trait RequestTrait
 * @package Wearesho\Evrotel\AutoDial
 */
trait RequestTrait
{
    /** @var string */
    protected $fileName;

    /** @var string */
    protected $phone;

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    protected function validatePhone(string $phone): void
    {
        if (!preg_match('/^380\d{9}$/', $phone)) {
            throw new \InvalidArgumentException("Invalid ukranian phone format: {$phone}");
        }
    }

    protected function validateFileName(string $fileName): void
    {
        if (!preg_match('/^.+\.wav$/', $fileName)) {
            throw new \InvalidArgumentException("File name {$fileName} does not have .wav extension");
        }
    }
}
