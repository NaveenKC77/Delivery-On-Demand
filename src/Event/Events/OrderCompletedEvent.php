<?php

namespace App\Event\Events;
use App\Enum\OrderStatus;

class OrderCompletedEvent extends AbstractOrderEvent
{
    public const EVENT_NAME = OrderStatus::COMPLETED->value;

}
