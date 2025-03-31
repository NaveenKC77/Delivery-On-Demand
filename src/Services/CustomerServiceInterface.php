<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;
use App\Entity\Customer;

interface CustomerServiceInterface extends EntityServicesInterface
{
    /**
     * Gets a QueryBuilder for all verified customers, used By Pagerrfanta
     *
     * @return QueryBuilder The Doctrine QueryBuilder instance
     */
    public function getAllVerifiedQueryBuilder(): QueryBuilder;



}
