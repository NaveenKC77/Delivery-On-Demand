<?php

namespace App\Services;

interface ServicesInterface
{
    public function getAll(): array;

    public function getOneById(int $id);

    public function add($entity): void;

    public function edit($entity);

    public function delete($entity);
}
