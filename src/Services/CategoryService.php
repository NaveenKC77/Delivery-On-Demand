<?php

namespace App\Services;

use App\Entity\EntityInterface;
use App\Repository\CategoryRepository;
use Doctrine\ORM\QueryBuilder;

class CategoryService extends AbstractEntityService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
        parent::__construct(repository: $categoryRepository);
    }

    public function getAll(): array
    {
        return $this->categoryRepository->findAllWithProducts();
    }
    public function getOneById(int $id): EntityInterface
    {
        return $this->categoryRepository->findOneById($id);
    }

    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->categoryRepository->getAllQueryBuilder();
    }


}
