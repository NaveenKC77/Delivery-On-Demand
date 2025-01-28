<?php

namespace App\Event\Events;

class ProductDeletedEvent extends AbstractProductEvent
{
    public function getAction(): string
    {
        return $this::DELETE;
    }

}
