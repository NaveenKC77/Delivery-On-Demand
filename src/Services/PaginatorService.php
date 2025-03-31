<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class PaginatorService implements PaginatorServiceInterface
{
    public function getPagination(QueryBuilder $qb, int $currentPage, int $maxPerPage): Pagerfanta
    {
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage($maxPerPage);
        $pagination->setCurrentPage($currentPage);

        return $pagination;
    }


}
