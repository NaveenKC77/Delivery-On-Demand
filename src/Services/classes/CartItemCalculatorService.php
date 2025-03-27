<?php

namespace App\Services;

class CartItemCalculatorService implements CartItemCalculatorServiceInterface
{
    public function calculateTotal($cartItem): float
    {
        $total = $cartItem->getQuantity() * $cartItem->getUnitPrice();
        return $total;
    }

    public function updateCartItemTotals($cartItem): void
    {
        //calculate new total
        $newTotal = $this->calculateTotal($cartItem);
        $cartItem->setTotal($newTotal);
    }
}
