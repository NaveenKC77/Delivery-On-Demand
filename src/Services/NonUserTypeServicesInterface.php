<?php

namespace App\Services;

interface NonUserTypeServicesInterface extends ServicesInterface
{
    /**
     * Summary of add
     * @param mixed $entity
     * @return void
     *              adding new object to db
     */
    public function add($entity): void;

    /**
     * Summary of edit
     * @param mixed $entity
     * @return void
     *              updating object in db
     */
    public function edit($entity);

    /**
     * Summary of delete
     * @param mixed $entity
     * @return void
     *              deleting object from db
     */
    public function delete($entity);
}
