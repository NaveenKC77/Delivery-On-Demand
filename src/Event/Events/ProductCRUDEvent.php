<?php

namespace App\Event\Events;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductCRUDEvent extends Event
{
    public function __construct(
        private Product $product,
        private string $action
    ) {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
