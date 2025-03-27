<?php

namespace App\Services;

class OrderCalculatorService
{
    public function calculateTax(int $subtotal, int $taxRate = 10): int
    {
        return (int) (($subtotal * $taxRate) / 100);
    }


    public function calculateTotal(int $subtotal, int $tax, int $shipping): int
    {
        return $subtotal + $tax + $shipping;
    }


    public function calculateShipping(): int
    {
        return 0;
    }

}
