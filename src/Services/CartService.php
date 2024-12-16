<?php

namespace App\Services;

use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    public function __construct(private CartRepository $cartRepository, private EntityManagerInterface $em)
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
        $this->em->persist($cart);
        $this->em->flush();
    }

    public function getCartFromCustomerId($customerId)
    {
        return $this->cartRepository->findOneByCustomerId($customerId);
    }
}
