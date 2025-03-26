<?php

namespace App\Services;

use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use Doctrine\ORM\QueryBuilder;

class CartItemService extends AbstractEntityService
{
    public function __construct(public CartItemRepository $cartItemRepository, private CartItemCalculatorService $cartItemCalculatorService)
    {
    }

    public function getAll():array{
        return $this->cartItemRepository->findAll();

    }

    public function getAllQueryBuilder(): QueryBuilder{
        return $this->cartItemRepository->getAllQueryBuilder();
    }
    public function getOneById(int $id):CartItem{
        return $this->cartItemRepository->findOneById($id);
    }
   
    /**
     * Summary of plusQuantity
     * @param mixed $id
     * @return void
     * add quantity of the single cartItem in cart
     */
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
        $this->save($cartItem);
    }

    /**
     * Summary of minusQuantity
     * @param mixed $id
     * @return void
     * reduce quantity of cartItem in cart
     */
    public function minusQuantity($id): void
    {
        $cartItem = $this->getOneById($id);

        $quantity = $cartItem->getQuantity();

        // if quantity == 0 , delete the item
        ($quantity == 1) ? $this->delete($cartItem) : $cartItem->setQuantity($quantity - 1);

        //calculate new total
        $this->resetCartItemNumbers($cartItem);
        $this->save($cartItem);
    }

    public function resetCartItemNumbers($cartItem): void
    {
        //calculate new total
        $newTotal = $this->cartItemCalculatorService->calculateTotal($cartItem);
        $cartItem->setTotal($newTotal);
    }
}
