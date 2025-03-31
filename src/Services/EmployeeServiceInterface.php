<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;
use App\Entity\Employee;

interface EmployeeServiceInterface extends EntityServicesInterface
{
    /**
     * Gets a QueryBuilder instance pre-configured for verified employees
     *
     * @return QueryBuilder Configured to filter only verified employees
     */
    public function getAllVerifiedQueryBuilder(): QueryBuilder;



}
