<?php

namespace App\Services;

use App\Repository\CategoryRepository;
use App\Entity\Category;

class CategoryService extends AbstractEntityService implements CategoryServiceInterface
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
        parent::__construct(repository: $categoryRepository);
    }

    /**
     * Get category by category id
     * Ensures type safety
     * @param int $id
     * @return \App\Entity\Category
     */
    public function getCategory(int $id): Category
    {
        return $this->getOneById($id);
    }
    protected function getEntityClass(): string
    {
        return Category::class;
    }

}
