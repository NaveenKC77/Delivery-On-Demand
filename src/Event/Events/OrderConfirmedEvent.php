<?php

namespace App\Event\Events;
use App\Enum\OrderStatus;

class OrderConfirmedEvent extends AbstractOrderEvent
{
    public const EVENT_NAME = OrderStatus::CONFIRMED->value;

}
