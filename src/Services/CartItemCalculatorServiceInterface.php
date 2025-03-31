<?php

namespace App\Services;

use App\Entity\CartItem;

interface CartItemCalculatorServiceInterface
{
    /**
     * Calculates the total price for a cart item (quantity × unit price)
     *
     * @param CartItem $cartItem The cart item entity
     * @return float The calculated total amount
     */
    public function calculateTotal(CartItem $cartItem): float;

    /**
     * Updates the cart item's total amount by recalculating it
     *
     * @param CartItem $cartItem The cart item entity to update
     */
    public function updateCartItemTotals(CartItem $cartItem): void;
}
