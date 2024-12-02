<?php

namespace App\Services;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService implements ServicesInterface
{

    public function __construct(private CategoryRepository $categoryRepository, private EntityManagerInterface $em) {}
    public function getAll(): array
    {
        return $this->categoryRepository->findAllWithProducts();
    }
    function add($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
    function delete($id)
    {
        $object = $this->categoryRepository->findOneById($id);
        $this->em->remove($object);
        $this->em->flush();
    }
    function edit($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
    function getOneById(int $id)
    {
        return $this->categoryRepository->findOneById($id);
    }
    function processUpload($imagePath, $uploadDir): string
    {
        return '';
    }
    function returnCardProperties(): array
    {
        return [];
    }
}
