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

    protected function getEntityClass(): string{
        return Category::class;
    }

}
