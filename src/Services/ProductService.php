<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getAll()
    {
        return $this->productRepository->findAll();
    }

    public function getOneById(int $id)
    {
        return $this->productRepository->find($id);
    }

    public function add(Product $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function edit()
    {
        $this->entityManager->flush();
    }

    public function returnCardProperties()
    {
        $products = $this->getAll();

        $productsCount = count($products);
        $availCount = 0;
        $unavailCount = 0;
        foreach ($products as $product) {
            if (true == $product->isAvailable()) {
                ++$availCount;
            } else {
                ++$unavailCount;
            }
        }

        return ['productsCount' => $productsCount, 'availableProductsCount' => $availCount,
            'unavailableProductsCount' => $unavailCount];
    }

    public function delete(Product $product)
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
