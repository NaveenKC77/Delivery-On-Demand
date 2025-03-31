<?php

namespace App\Services;

use App\Entity\Cart;

class CartCalculatorService implements CartCalculatorServiceInterface
{
    public function __construct(private CartItemServiceInterface $cartItemService)
    {

    }

    public function calculateQuantity($cartItems): int
    {
        $total = array_reduce(array: $cartItems->toArray(), callback: function ($q, $item) {
            return $q + $item->getQuantity();
        }, initial: 0);

        return $total;
    }


    public function calculateTotal($cartItems): int
    {
        $total = array_reduce(array: $cartItems->toArray(), callback: function ($sum, $item) {
            return $sum + $item->getTotal();
        }, initial: 0);

        return $total;
    }


    public function updateCartTotals(Cart $cart): void
    {
        $cart->setQuantity($this->calculateQuantity($cart->getCartItems()));
        $cart->setTotal($this->calculateTotal($cart->getCartItems()));

    }


}
