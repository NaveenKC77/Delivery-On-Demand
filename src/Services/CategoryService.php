<?php

namespace App\Services;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\QueryBuilder;

class CategoryService implements NonUserTypeServicesInterface
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function getAll(): array
    {
        return $this->categoryRepository->findAllWithProducts();
    }
    public function getOneById(int $id): Category | null
    {
        return $this->categoryRepository->findOneById($id);
    }

    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->categoryRepository->getAllQueryBuilder();
    }

    public function add($entity): void
    {
        $this->categoryRepository->save($entity);
    }

    public function delete($id)
    {
        $object = $this->categoryRepository->findOneById($id);
        $this->categoryRepository->delete($object);

    }

    public function edit($entity)
    {
        $this->categoryRepository->save($entity);
    }

}
