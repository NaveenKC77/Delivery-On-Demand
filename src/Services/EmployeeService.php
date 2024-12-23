<?php

namespace App\Services;

use App\Repository\EmployeeRepository;

class EmployeeService implements ServicesInterface
{
    public function __construct(private EmployeeRepository $employeeRepository)
    {
    }

    public function add($entity): void
    {
        $this->employeeRepository->geteEntityManager()->persist($entity);
        $this->employeeRepository->geteEntityManager()->flush();
    }

    public function delete($entity)
    {
        $this->employeeRepository->geteEntityManager()->remove($entity);
        $this->employeeRepository->geteEntityManager()->flush();
    }

    public function edit($entity)
    {
        $this->employeeRepository->geteEntityManager()->persist($entity);
        $this->employeeRepository->geteEntityManager()->flush();
    }

    public function getAll(): array
    {
        return $this->employeeRepository->findAll();
    }

    public function getOneById(int $id)
    {
        $this->employeeRepository->findOneById($id);
    }

    public function getAllQueryBuilder()
    {
        return $this->employeeRepository->getAllQueryBuilder();
    }

    public function getAllVerifiedQueryBuilder()
    {
        return $this->employeeRepository->getAllVerifiedQueryBuilder();
    }
}
