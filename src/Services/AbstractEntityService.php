<?php

namespace App\Services;

use App\Entity\EntityInterface;
use App\Repository\EntityRepositoryInterface;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractEntityService implements EntityServicesInterface
{
    public function __construct(private EntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getRepository(): EntityRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Get all entities
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Get the QueryBuilder for all entities
     *
     * @return QueryBuilder
     */
    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->repository->getAllQueryBuilder();
    }

    /**
     * Get class for the entity of repository, ensures type safety
     * @return void
     */
    abstract protected function getEntityClass(): string;

    /**
     * Get one entity by its ID
     *
     * @param int $id
     * @return EntityInterface
     */
    public function getOneById(int $id): EntityInterface
    {
        $entity = $this->repository->find($id);
        if (!$entity instanceof ($this->getEntityClass())) {
            throw new \RuntimeException('Unexpected entity type');
        }
        return $entity;
    }


    public function save(EntityInterface $entity): void
    {
        $this->repository->save($entity);
    }

    public function delete(EntityInterface $entity): void
    {
        $this->repository->delete($entity);
    }


}
