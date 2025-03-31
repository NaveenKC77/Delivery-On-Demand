<?php

namespace App\Services;

use App\Entity\CartItem;

interface CartItemServiceInterface extends EntityServicesInterface
{
    /**
     * Returns single CartItem by id, ensures type safety
     * @param int $id
     * @return \App\Entity\CartItem
     */
    public function getCartItem(int $id): ?CartItem;
    /**
     * Increases the quantity of a cart item, respecting product stock limits
     *
     * @param int|string $id The ID of the cart item to modify
     * @throws \RuntimeException If the operation fails
     */
    public function plusQuantity(int $id): void;

    /**
     * Decreases the quantity of a cart item, removing it if quantity reaches zero
     *
     * @param int|string $id The ID of the cart item to modify
     * @throws \RuntimeException If the operation fails
     */
    public function minusQuantity(int $id): void;
}
