<?php

namespace App\Event\Events;

use App\Enum\OrderStatus;

class OrderPlacedEvent extends AbstractOrderEvent
{
    public const EVENT_NAME = OrderStatus::PENDING->value;

}
