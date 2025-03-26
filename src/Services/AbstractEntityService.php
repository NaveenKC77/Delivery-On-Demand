<?php

namespace App\Services;

use App\Entity\EntityInterface;
use App\Repository\EntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;


abstract class AbstractEntityService implements EntityServicesInterface{


    public function __construct( private EntityRepositoryInterface $repository){
        $this->repository=$repository;
    }

    /**
     * Get the EntityManager from the repository.
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface{  
        return $this->repository->getEntityManager();
    }

    /**
     * Persist an entity.
     *
     * @param EntityInterface $entity
     * @return void
     */
    public function save(EntityInterface $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete an entity.
     *
     * @param EntityInterface $entity
     * @return void
     */
    public function delete(EntityInterface $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Get all entities (to be implemented by child class).
     *
     * @return array
     */
    abstract public function getAll(): array;

    /**
     * Get the QueryBuilder for all entities (to be implemented by child class).
     *
     * @return QueryBuilder
     */
    abstract public function getAllQueryBuilder(): QueryBuilder;

    /**
     * Get one entity by its ID (to be implemented by child class).
     *
     * @param int $id
     * @return EntityInterface
     */
    abstract public function getOneById(int $id): EntityInterface;


}