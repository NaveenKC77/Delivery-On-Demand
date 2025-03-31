<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;

class ProductService extends AbstractEntityService implements ProductServiceInterface
{
    public function __construct(private ProductRepository $repository, private FileUploadService $fileUploadService)
    {
        parent::__construct($repository);
    }

    public function getProduct(int $id): Product
    {
        return  $this->getOneById($id);
    }

    /**
     * Returns first 3 products
     */
    public function getFeaturedProducts(): array
    {
        return $this->repository->findFeaturedProducts();
    }

    protected function getEntityClass(): string
    {
        return Product::class;
    }

    public function getProductsByCategoriesQb(int $categoryId): QueryBuilder
    {
        $qb = $this->repository->getByCategoriesQueryBuilder($categoryId);
        return $qb;
    }


}
