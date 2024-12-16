<?php

namespace App\Services;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService implements ServicesInterface
{
    public function __construct(private EmployeeRepository $employeeRepository, private EntityManagerInterface $em)
    {
    }

    public function add($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function edit($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
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
