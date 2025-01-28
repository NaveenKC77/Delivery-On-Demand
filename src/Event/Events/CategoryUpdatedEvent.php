<?php

namespace App\Event\Events;

class CategoryUpdatedEvent extends AbstractCategoryEvent
{
    public function getAction(): string
    {
        return $this::UPDATE;
    }

}
