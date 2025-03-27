<?php

namespace App\Services;

use App\Entity\Employee;
use App\Repository\EmployeeRepositoryInterface;
use Doctrine\ORM\QueryBuilder;

class EmployeeService extends AbstractEntityService implements EmployeeServiceInterface
{
    public function __construct(private EmployeeRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function getEntityClass(): string{
        return Employee::class;
    }


    public function getAllVerifiedQueryBuilder(): QueryBuilder
    {
        return $this->repository->getAllVerifiedQueryBuilder();
    }
}
