<?php

declare(strict_types=1);

namespace Wearesho\Evrotel\Statistics\Call;

use Wearesho\BaseCollection;
use Wearesho\Evrotel;

/**
 * Class Collection
 * @package Wearesho\Evrotel\Statistics\Call
 */
class Collection extends BaseCollection
{
    /**
     * @inheritdoc
     */
    public function type(): string
    {
        return Evrotel\Statistics\Call::class;
    }
}
