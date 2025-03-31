<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartItem;

interface CartServiceInterface extends EntityServicesInterface
{
    /**
     * Adds an item to the cart and updates all totals
     *
     * @param Cart $cart The cart entity
     * @param CartItem $cartItem The cart item to add
     */
    public function addCartItem(Cart $cart, CartItem $cartItem): void;

    /**
     * Removes an item from the cart and updates all totals
     *
     * @param Cart $cart The cart entity
     * @param CartItem $cartItem The cart item to remove
     */
    public function removeCartItem(Cart $cart, CartItem $cartItem): void;

    /**
     * Checks if an item already exists in the cart
     *
     * @param Cart $cart The cart entity
     * @param CartItem $cartItem The cart item to check
     * @return bool True if item exists, false otherwise
     */
    public function checkItemExists(Cart $cart, CartItem $cartItem): bool;
}
