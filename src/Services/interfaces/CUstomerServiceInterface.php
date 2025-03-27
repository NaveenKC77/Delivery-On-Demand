<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;

interface CustomerServiceInterface
{
    /**
     * Gets a QueryBuilder for all verified customers, used By Pagerrfanta
     *
     * @return QueryBuilder The Doctrine QueryBuilder instance
     */
    public function getAllVerifiedQueryBuilder(): QueryBuilder;
}
