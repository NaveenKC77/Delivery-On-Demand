<?php

namespace App\Services;

use App\Entity\Product;
use App\Event\Events\ProductCreatedEvent;
use App\Event\Events\ProductDeletedEvent;
use App\Event\Events\ProductUpdatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductEventDispatcherService
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatchProductCreatedEvent(Product $product): void
    {
        $this->eventDispatcher->dispatch(new ProductCreatedEvent($product), ProductCreatedEvent::class);
    }

    public function dispatchProductUpdatedEvent(Product $product): void
    {
        $this->eventDispatcher->dispatch(new ProductUpdatedEvent($product), ProductUpdatedEvent::class);
    }

    public function dispatchProductDeletedEvent(Product $product): void
    {
        $this->eventDispatcher->dispatch(new ProductDeletedEvent($product), ProductDeletedEvent::class);
    }
}
