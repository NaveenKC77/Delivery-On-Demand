<?php

namespace App\Services;

use App\Entity\Category;
use App\Event\Events\CategoryCreatedEvent;
use App\Event\Events\CategoryDeletedEvent;
use App\Event\Events\CategoryUpdatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CategoryEventDispatcherService
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatchCategoryCreatedEvent(Category $category): void
    {
        $this->eventDispatcher->dispatch(new CategoryCreatedEvent($category), CategoryCreatedEvent::class);
    }

    public function dispatchCategoryUpdatedEvent(Category $category): void
    {
        $this->eventDispatcher->dispatch(new CategoryUpdatedEvent($category), CategoryUpdatedEvent::class);
    }

    public function dispatchCategoryDeletedEvent(Category $category): void
    {
        $this->eventDispatcher->dispatch(new CategoryDeletedEvent($category), CategoryDeletedEvent::class);
    }
}
