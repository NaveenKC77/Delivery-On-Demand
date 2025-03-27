<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface CustomerRepositoryInterface extends EntityRepositoryInterface
{
    public function getAllVerifiedQueryBuilder(): QueryBuilder;
}
