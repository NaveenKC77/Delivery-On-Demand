<?php

namespace App\Services;

use App\Entity\User;

interface CartManagerInterface
{
    /**
     * Creates and persists a new cart for the given user
     *
     * @param User $user The user to create a cart for
     * @throws CartCreationException If cart cannot be created
     */
    public function createCart(User $user): void;
}
