<?php

namespace App\Services;

use App\Repository\CartItemRepository;

class CartItemService
{
    public function __construct(private CartItemRepository $cartItemRepository)
    {
    }

    public function add($entity): void
    {
        $this->cartItemRepository->persist($entity);
        $this->cartItemRepository->flush();
    }

    public function delete($entity)
    {
        $this->cartItemRepository->remove($entity);
        $this->cartItemRepository->flush();
    }

    public function getOneById(int $id)
    {
        return $this->cartItemRepository->findOneById($id);
    }

    public function plusQuantity($id)
    {
        $cartItem = $this->getOneById($id);

        $quantity = $cartItem->getQuantity();

        if ($quantity >= 99) {
            $cartItem->setQuantity(99);
        } else {
            $cartItem->setQuantity($quantity + 1);
        }
        $this->cartItemRepository->persist($cartItem);
        $this->cartItemRepository->flush();
    }

    public function minusQuantity($id)
    {
        $cartItem = $this->getOneById($id);

        $quantity = $cartItem->getQuantity();

        if (1 == $quantity) {
            $this->delete($cartItem);
        } else {
            $cartItem->setQuantity($quantity - 1);
        }
        $this->cartItemRepository->persist($cartItem);
        $this->cartItemRepository->flush();
    }
}
