<?php

namespace App\Services;

use App\Entity\CartItem;

interface CartItemServiceInterface {
    public function save(CartItem $cartItem): void;

    public function delete(CartItem $cartItem): void;
}
