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
        $this->cartItemRepository->getEntityManager()->persist($entity);
        $this->cartItemRepository->getEntityManager()->flush();
    }

    public function delete($entity)
    {
        $this->cartItemRepository->getEntityManager()->remove($entity);
        $this->cartItemRepository->getEntityManager()->flush();
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
        $this->cartItemRepository->getEntityManager()->persist($cartItem);
        $this->cartItemRepository->getEntityManager()->flush();
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
        $this->cartItemRepository->getEntityManager()->persist($cartItem);
        $this->cartItemRepository->getEntityManager()->flush();
    }
}
