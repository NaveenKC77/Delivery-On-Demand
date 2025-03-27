<?php

namespace App\Services;

use App\Entity\Customer;
use App\Repository\CustomerRepositoryInterface;
use Doctrine\ORM\QueryBuilder;

class CustomerService extends AbstractEntityService implements CustomerServiceInterface
{
    public function __construct(private CustomerRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }


    protected function getEntityClass(): string{
        return Customer::class;
    }
    public function getAllVerifiedQueryBuilder(): QueryBuilder
    {
        return $this->repository->getAllVerifiedQueryBuilder();
    }


}
