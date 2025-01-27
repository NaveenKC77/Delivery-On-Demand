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
        // get stock of the product selected
        $stock = $cartItem->getProduct()->getStock();
        // get current quantity
        $quantity = $cartItem->getQuantity();
        // quantity cannot exceed the product stock
        ($quantity >= $stock) ? $cartItem->setQuantity($stock) : $cartItem->setQuantity($quantity + 1);

        $this->cartItemRepository->getEntityManager()->persist($cartItem);
        $this->cartItemRepository->getEntityManager()->flush();
    }

    public function minusQuantity($id)
    {
        $cartItem = $this->getOneById($id);

        $quantity = $cartItem->getQuantity();

        // if quantity == 0 , delete the item
        ($quantity == 1) ? $this->delete($cartItem) : $cartItem->setQuantity($quantity - 1);

        $this->cartItemRepository->getEntityManager()->persist($cartItem);
        $this->cartItemRepository->getEntityManager()->flush();
    }
}
