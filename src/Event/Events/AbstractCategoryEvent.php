<?php

namespace App\Event\Events;

use App\Entity\Category;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractCategoryEvent extends Event
{
    public function __construct(
        private Category $category,
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
        return 'Category';
    }
    public function getCategory(): Category
    {
        return $this->category;
    }

}
