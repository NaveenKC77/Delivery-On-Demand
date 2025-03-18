<?php

namespace App\Event\Events;

use App\Entity\Category;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractCategoryEvent extends Event implements AppLoggerEventInterface 
{
    public function __construct(
        private Category $category,
    ) {
    }

    protected const EVENT_NAME = "";


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
        return 'Category';
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

}
