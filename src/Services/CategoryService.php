<?php

namespace App\Services;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService implements ServicesInterface
{
    public function __construct(private CategoryRepository $categoryRepository, private EntityManagerInterface $em)
    {
    }

    public function getAll(): array
    {
        return $this->categoryRepository->findAllWithProducts();
    }

    public function add($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete($id)
    {
        $object = $this->categoryRepository->findOneById($id);
        $this->em->remove($object);
        $this->em->flush();
    }

    public function edit($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
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
