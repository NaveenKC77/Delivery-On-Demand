<?php

namespace App\Services;

class CartItemCalculatorService
{
    public function calculateTotal($cartItem)
    {
        $total = $cartItem->getQuantity() * $cartItem->getUnitPrice();
        return $total;
    }
}
