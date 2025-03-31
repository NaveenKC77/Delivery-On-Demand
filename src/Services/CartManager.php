<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\User;
use App\Repository\CartRepositoryInterface;

/**
 * Handles initialization of cart while registering user
 * gets cart for the customer
 */
class CartManager implements CartManagerInterface
{
    public function __construct(private CartRepositoryInterface $repository)
    {
    }
    public function createCart(User $user): void
    {
        // create cart
        $cart = new Cart();
        $cart->setCustomer(customer: $user->getCustomer());

        // persist to db
        $this->repository->save($cart);
    }


}
