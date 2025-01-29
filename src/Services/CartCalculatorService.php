<?php

namespace App\Services;

class CartCalculatorService
{
    public function calculateQuantity($cartItems)
    {
        $total = array_reduce($cartItems->toArray(), function ($q, $item) {
            return $q + $item->getQuantity();
        }, 0);

        return $total;
    }

    public function calculateTotal($cartItems): int
    {
        $total = array_reduce($cartItems->toArray(), function ($sum, $item) {
            return $sum + $item->getTotal();
        }, 0);

        return $total;
    }
}
