<?php

namespace App\Event\Events;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

class OrderPlacedEvent extends Event
{
    public function __construct(
        private Order $order
    ) {
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

}
