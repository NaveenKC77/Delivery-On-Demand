<?php

namespace App\Services;

use App\Repository\CartRepository;

class CartService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }

    public function getOneById(int $id)
    {
        return $this->cartRepository->findOneById($id);
    }

    public function addCartItem($cartItem)
    {
        $cart = $this->cartRepository->findOneById($cartItem->getCart()->getId());
        $cart->addCartItem($cartItem);
        $this->cartRepository->persist($cart);
        $this->cartRepository->flush();
    }

    public function removeCartItem($cartItem)
    {
        $cart = $this->cartRepository->findOneById($cartItem->getCart()->getId());
        $cart->removeCartItem($cartItem);
        $this->cartRepository->persist($cart);
        $this->cartRepository->flush();
    }

    public function getCartFromCustomerId($customerId)
    {
        return $this->cartRepository->findOneByCustomerId($customerId);
    }
}
