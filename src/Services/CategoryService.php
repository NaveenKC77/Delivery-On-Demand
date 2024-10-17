<?php

namespace App\Services;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getAll()
    {
        return $this->categoryRepository->findAll();
    }

    public function getOneById(int $id)
    {
        return $this->categoryRepository->find($id);
    }

    public function returnCardProperties()
    {
        $categories = $this->getAll();

        $totalCount = count($categories);
        $activeCount = 0;
        $inactiveCount = 0;
        foreach ($categories as $category) {
            if (true == $category->isActive()) {
                ++$activeCount;
            } else {
                ++$inactiveCount;
            }
        }

        return ['totalCount' => $totalCount, 'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount];
    }

    public function add(Category $category)
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function edit()
    {
        $this->entityManager->flush();
    }

    public function delete(Category $category)
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    // public function getActiveCategories(){
    //     $activeCategories=$this->categoryRepository->findActiveCategories();

    //     return $activeCategories;
    // }
}
