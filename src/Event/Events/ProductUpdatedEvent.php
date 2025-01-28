<?php

namespace App\Event\Events;

class ProductUpdatedEvent extends AbstractProductEvent
{
    public function getAction(): string
    {
        return $this::UPDATE;
    }

}
