<?php

namespace App\Repository;

use App\Entity\EntityInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Summary of EntityRepositoryInterface
 * to be followed by all the repository classes
 
 */
interface EntityRepositoryInterface
{
    public function save(EntityInterface $entity): void;
    public function delete(EntityInterface $entity): void;
    public function getAllQueryBuilder():QueryBuilder;

}
