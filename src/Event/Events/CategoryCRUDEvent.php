<?php

namespace App\Event\Events;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class CategoryCRUDEvent extends Event
{
    public function __construct(
        private Category $category,
        private string $action
    ) {
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
