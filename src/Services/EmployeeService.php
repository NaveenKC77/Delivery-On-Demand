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
        $this->employeeRepository->persist($entity);
        $this->employeeRepository->flush();
    }

    public function delete($entity)
    {
        $this->employeeRepository->remove($entity);
        $this->employeeRepository->flush();
    }

    public function edit($entity)
    {
        $this->employeeRepository->persist($entity);
        $this->employeeRepository->flush();
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
