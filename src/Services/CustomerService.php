<?php

namespace App\Services;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\QueryBuilder;

class CustomerService implements UserTypeServicesInterface
{
    public function __construct(private CustomerRepository $customerRepository)
    {
    }

    public function getAll(): array
    {
        return $this->customerRepository->findAll();
    }

    public function getOneById(int $id): Customer | null
    {
        return $this->customerRepository->findOneById($id);
    }

    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->customerRepository->getAllQueryBuilder();
    }

    public function getAllVerifiedQueryBuilder(): QueryBuilder
    {
        return $this->customerRepository->getAllVerifiedQueryBuilder();
    }


}
