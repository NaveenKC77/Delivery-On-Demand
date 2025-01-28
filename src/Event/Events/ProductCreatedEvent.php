<?php

namespace App\Event\Events;

class ProductCreatedEvent extends AbstractProductEvent
{
    public function getAction(): string
    {
        return $this::CREATE;
    }

}
