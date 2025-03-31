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

    /**
     * Get Employee by id
     * ENsures type safety
     * @param int $id
     * @return Employee
     */
    private function getEmployee(int $id): Employee
    {
        return $this->repository->find($id);
    }
    protected function getEntityClass(): string
    {
        return Employee::class;
    }


    public function getAllVerifiedQueryBuilder(): QueryBuilder
    {
        return $this->repository->getAllVerifiedQueryBuilder();
    }
}
