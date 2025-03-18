<?php

namespace App\Event\Events;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractProductEvent extends Event implements AppLoggerEventInterface
{
    public function __construct(
        private Product $product,
    ) {
    }
    protected const EVENT_NAME ="";
    /**
     * Summary of getAction
     * @return string
     *                return action type 'Create','Update' or 'Delete'
     */
    public function getAction(): string{
        return static::EVENT_NAME;
    }

    public function getEntityType(): string
    {
        return 'Product';
    }
    public function getProduct(): Product
    {
        return $this->product;
    }

}
