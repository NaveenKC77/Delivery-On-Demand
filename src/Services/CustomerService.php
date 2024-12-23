<?php

namespace App\Services;

use App\Repository\CustomerRepository;

class CustomerService implements ServicesInterface
{
    public function __construct(private CustomerRepository $customerRepository)
    {
    }

    public function add($entity): void
    {
    }

    public function delete($entity)
    {
        $this->customerRepository->getEntityManager()->remove($entity);
        $this->customerRepository->getEntityManager()->flush();
    }

    public function edit($entity)
    {
        $this->customerRepository->getEntityManager()->persist($entity);
        $this->customerRepository->getEntityManager()->flush();
    }

    public function getAll(): array
    {
        return $this->customerRepository->findAll();
    }

    public function getOneById(int $id)
    {
        return $this->customerRepository->findOneById($id);
    }

    public function getAllQueryBuilder()
    {
        return $this->customerRepository->getAllQueryBuilder();
    }

    public function getAllVerifiedQueryBuilder()
    {
        return $this->customerRepository->getAllVerifiedQueryBuilder();
    }
}
