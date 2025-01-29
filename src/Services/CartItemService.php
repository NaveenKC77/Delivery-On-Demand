<?php

namespace App\Services;

use App\Entity\CartItem;
use App\Repository\CartItemRepository;

class CartItemService
{
    public function __construct(private CartItemRepository $cartItemRepository, private CartItemCalculatorService $cartItemCalculatorService)
    {
    }

    public function add(CartItem $cartItem): void
    {
        //calculate new total
        $newTotal = $this->cartItemCalculatorService->calculateTotal($cartItem);
        $cartItem->setTotal($newTotal);
        $this->cartItemRepository->save($cartItem);
    }

    public function delete($entity): void
    {
        $this->cartItemRepository->delete($entity);
    }

    public function getOneById(int $id): mixed
    {
        return $this->cartItemRepository->findOneById($id);
    }

    public function plusQuantity($id): void
    {
        $cartItem = $this->getOneById($id);
        // get stock of the product selected
        $stock = $cartItem->getProduct()->getStock();
        // get current quantity
        $quantity = $cartItem->getQuantity();
        // quantity cannot exceed the product stock
        ($quantity >= $stock) ? $cartItem->setQuantity($stock) : $cartItem->setQuantity($quantity + 1);
        //calculate new total
        $this->resetCartItemNumbers($cartItem);
        $this->cartItemRepository->save($cartItem);
    }

    public function minusQuantity($id): void
    {
        $cartItem = $this->getOneById($id);

        $quantity = $cartItem->getQuantity();

        // if quantity == 0 , delete the item
        ($quantity == 1) ? $this->delete($cartItem) : $cartItem->setQuantity($quantity - 1);

        //calculate new total
        $this->resetCartItemNumbers($cartItem);
        $this->cartItemRepository->save($cartItem);
    }

    public function resetCartItemNumbers($cartItem): void
    {
        //calculate new total
        $newTotal = $this->cartItemCalculatorService->calculateTotal($cartItem);
        $cartItem->setTotal($newTotal);
    }
}
