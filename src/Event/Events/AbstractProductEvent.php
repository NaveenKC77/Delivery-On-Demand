<?php

namespace App\Event\Events;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractProductEvent extends Event
{
    public function __construct(
        private Product $product,
    ) {
    }

    protected const CREATE = "Create";
    protected const UPDATE = "Update";
    protected const DELETE = "Delete";
    /**
     * Summary of getAction
     * @return string
     *                return action type 'Create','Update' or 'Delete'
     */
    abstract protected function getAction(): string;

    public function getEntityType()
    {
        return 'Product';
    }
    public function getProduct(): Product
    {
        return $this->product;
    }

}
