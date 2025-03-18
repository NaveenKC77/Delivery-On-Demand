<?php

namespace App\Services;

use App\Entity\EntityInterface;
use Doctrine\ORM\QueryBuilder;

interface ServicesInterface
{
    /**
     * Summary of getAll
     * @return array
     *               return all objects of the entity type given
     */
    public function getAll(): array;

    /**
     * Summary of getOneById
     * @param int $id
     * @return \App\Entity\EntityInterface|null
     *                                          return single object using id
     */
    public function getOneById(int $id): EntityInterface | null;

    /**
     * Summary of getAllQueryBuilder
     * @return \Doctrine\ORM\QueryBuilder
     *                                    queryBuilder for all the objects of entity
     */
    public function getAllQueryBuilder(): QueryBuilder;

}
