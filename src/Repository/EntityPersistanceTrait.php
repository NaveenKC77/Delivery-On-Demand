<?php

namespace App\Repository;

use App\Entity\EntityInterface;

/**
 * Summary of EntityPersistanceTrait
 * Explicitly for Doctrine Created Repositories
 * Functions to persist Data On Database
 */
trait EntityPersistanceTrait {

    /**
     * Summary of save
     * @param \App\Entity\EntityInterface $entity
     * save data to databse
     * @return void
     */
    public function save(EntityInterface $entity): void{
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Summary of delete
     * @param \App\Entity\EntityInterface $entity
     * Delete Data From Database
     * @return void
     */
    public function delete(EntityInterface $entity): void{
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}