<?php

namespace App\Event\Events;
use App\Enum\OrderStatus;

class OrderCancelledEvent extends AbstractOrderEvent
{
    public const EVENT_NAME = OrderStatus::CANCELLED->value;

}
