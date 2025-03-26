<?php

namespace App\Services;

use App\Entity\EntityInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Summary of EntityServicesInterface
 * Interface for ENtity Related Services
 */
interface EntityServicesInterface{

    /**
     * Summary of getAll
     * Get all rows from tables
     * @return array
     */
    public function getAll():array;

    /**
     * Summary of getAllQueryBuilder
     * Get QueryBuilder to return all items
     * Used for pagination
     * @return QueryBuilder
     */
    public function getAllQueryBuilder(): QueryBuilder;

    /**
     * Summary of getOneById
     * @param int $id
     * Get a single row by id
     * @return EntityInterface
     */
    public function getOneById(int $id):EntityInterface;

    /**
     * Summary of save
     * @param \App\Entity\EntityInterface $entity
     * Persist Data to Db
     * @return void
     */
    public function save(EntityInterface $entity): void;

    /**
     * Summary of delete
     * @param \App\Entity\EntityInterface $entity
     * Delete Data from Db
     * @return void
     */
    public function delete(EntityInterface $entity): void;   


}