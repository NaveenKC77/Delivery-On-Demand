<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface ProductRepositoryInterface extends EntityRepositoryInterface
{
    public function findFeaturedProducts(): array;

    public function getByCategoriesQueryBuilder(int $categoryId):QueryBuilder;
}
