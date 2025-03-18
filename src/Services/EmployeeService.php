<?php

namespace App\Services;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\QueryBuilder;

class EmployeeService implements UserTypeServicesInterface
{
    public function __construct(private EmployeeRepository $employeeRepository)
    {
    }

    public function getAll(): array
    {
        return $this->employeeRepository->findAll();
    }

    public function getOneById(int $id): Employee | null
    {
        return $this->employeeRepository->findOneById($id);
    }

    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->employeeRepository->getAllQueryBuilder();
    }

    public function getAllVerifiedQueryBuilder(): QueryBuilder
    {
        return $this->employeeRepository->getAllVerifiedQueryBuilder();
    }
}
