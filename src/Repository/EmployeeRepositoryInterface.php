<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface EmployeeRepositoryInterface extends EntityRepositoryInterface
{
    public function getAllVerifiedQueryBuilder(): QueryBuilder;
}
