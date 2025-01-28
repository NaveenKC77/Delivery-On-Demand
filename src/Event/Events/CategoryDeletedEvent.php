<?php

namespace App\Event\Events;

class CategoryDeletedEvent extends AbstractCategoryEvent
{
    public function getAction(): string
    {
        return $this::DELETE;
    }

}
