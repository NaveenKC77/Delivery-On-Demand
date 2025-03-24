<?php

namespace App\Event\Events;

use App\Entity\Order;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractOrderEvent extends Event implements AppLoggerEventInterface
{
    public function __construct(
        private Order $order,
    ) {
    }
    protected const EVENT_NAME ="";
    /**
     * Summary of getAction
     * @return string
     *                return action type 'Pending,Confirmed,Processing,Completed,Cancelled'
     */
    public function getAction(): string{
        return static::EVENT_NAME;
    }

    public function getEntityType(): string
    {
        return 'Order';
    }
    public function getOrder(): Order
    {
        return $this->order;
    }

}
