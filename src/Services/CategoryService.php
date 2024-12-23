<?php

namespace App\Services;

use App\Repository\CategoryRepository;

class CategoryService implements ServicesInterface
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function getAll(): array
    {
        return $this->categoryRepository->findAllWithProducts();
    }

    public function add($entity): void
    {
        $this->categoryRepository->getEntityManager()->persist($entity);
        $this->categoryRepository->getEntityManager()->flush();
    }

    public function delete($id)
    {
        $object = $this->categoryRepository->findOneById($id);
        $this->categoryRepository->getEntityManager()->remove($object);
        $this->categoryRepository->getEntityManager()->flush();
    }

    public function edit($entity)
    {
        $this->categoryRepository->getEntityManager()->persist($entity);
        $this->categoryRepository->getEntityManager()->flush();
    }

    public function getOneById(int $id)
    {
        return $this->categoryRepository->findOneById($id);
    }

    public function getAllQueryBuilder()
    {
        return $this->categoryRepository->getAllQueryBuilder();
    }
}
