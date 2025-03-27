<?php

namespace App\Services;

use App\Entity\Cart;

interface CartCalculatorServiceInterface
{
    /**
     * Calculates the total quantity of all items in the cart
     *
     * @param mixed $cartItems Collection/array of cart items
     * @return int Total quantity
     */
    public function calculateQuantity($cartItems): int;

    /**
     * Calculates the total price of all items in the cart
     *
     * @param mixed $cartItems Collection/array of cart items
     * @return int Total price (in smallest currency unit, e.g., cents)
     */
    public function calculateTotal($cartItems): int;

    /**
     * Updates all calculated totals on the cart entity
     *
     * @param Cart $cart The cart entity to update
     */
    public function updateCartTotals(Cart $cart): void;
}
