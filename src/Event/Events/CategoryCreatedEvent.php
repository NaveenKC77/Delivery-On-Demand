<?php

namespace App\Event\Events;

class CategoryCreatedEvent extends AbstractCategoryEvent
{
    public function getAction(): string
    {
        return $this::CREATE;
    }

}
