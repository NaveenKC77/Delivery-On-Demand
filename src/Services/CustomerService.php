<?php

namespace App\Services;

use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomerService implements ServicesInterface
{
    public function __construct(private CustomerRepository $customerRepository, private EntityManagerInterface $em)
    {
    }

    public function add($entity): void
    {
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
