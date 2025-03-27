<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;

class ProductService implements NonUserTypeServicesInterface
{
    public function __construct(private ProductRepository $productRepository, private FileUploadService $fileUploadService)
    {
    }

    /**
     * return array of all products joined with categories
     */
    public function getAll(): array
    {
        return $this->productRepository->findAllWithCategories();
    }
    /**
     *return single row of product
     */
    public function getOneById(int $id): Product | null
    {
        return $this->productRepository->findOneById($id);
    }

    /**
     * Returns first 3 products
     */
    public function getFeaturedProducts()
    {
        return $this->productRepository->findFeaturedProducts();
    }

    /**
     * Adds a new product
     */
    public function add($entity): void
    {
        $this->productRepository->save($entity);
    }

    /**
     * Deletes a product
     */
    public function delete($entity)
    {
        $this->productRepository->delete($entity);
    }

    /**
     * Updates already existing product
     */
    public function edit($entity)
    {
        $this->productRepository->save($entity);
    }

    /**
     *    queryBuilder to get All Products with categories joined
     */
    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->productRepository->getAllQueryBuilder();
    }
}
