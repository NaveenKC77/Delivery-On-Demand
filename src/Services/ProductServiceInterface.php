<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;

interface ProductServiceInterface extends EntityServicesInterface
{
    /**
     * Get product by id
     * Ensures type safety
     * @param int $id
     * @return Product
     */
    public function getProduct(int $id): Product;
    /**
     * returns featured products; logic not confirmed yet
     * currently returns first 3 products
     * @return array
     */
    public function getFeaturedProducts(): array;

    /**
     * Get products by categories
     * Return QueryBuilder for pagination
     * @param \App\Entity\Category $category
     * @return QueryBuilder
     */
    public function getProductsByCategoriesQb(int $categoryId): QueryBuilder;


}
