<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;

interface PaginatorServiceInterface
{
    /**
     * Creates and configures a Pagerfanta instance for pagination
     *
     * @param QueryBuilder $qb The Doctrine query builder to paginate
     * @param int $currentPage The current page number (1-based)
     * @param int $maxPerPage Maximum number of items per page
     * @return Pagerfanta Configured pagination instance
     */
    public function getPagination(QueryBuilder $qb, int $currentPage, int $maxPerPage): Pagerfanta;
}
