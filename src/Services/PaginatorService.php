<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class PaginatorService{

    public function __construct(){

    }


    /**
     * Summary of getPagination
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param int $currentPage
     * @param int $maxPerPage
     * @return \Pagerfanta\Pagerfanta
     *                                Utilizes PagerFanta to provide pagination
     */
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