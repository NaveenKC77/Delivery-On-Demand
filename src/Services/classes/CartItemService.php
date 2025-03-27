<?php

namespace App\Services;

use App\Entity\CartItem;
use App\Repository\CartItemRepositoryInterface;
use Exception;

class CartItemService extends AbstractEntityService {
    public function __construct(private CartItemRepositoryInterface $repository, private CartItemCalculatorServiceInterface $cartItemCalculatorService)
    {
        parent::__construct($repository);
    }

    protected function getEntityClass(): string{
        return CartItem::class;
    }

    public function getCartItem(int $id): CartItem {
        return $this->getOneById($id);
    }

    public function plusQuantity($id): void
    {

        try {
            $cartItem = $this->getCartItem($id);

            // get stock of the product selected
            $stock = $cartItem->getProduct()->getStock();
            // get current quantity
            $quantity = $cartItem->getQuantity();
            // quantity cannot exceed the product stock
            ($quantity >= $stock) ? $cartItem->setQuantity($stock) : $cartItem->setQuantity($quantity + 1);
            //calculate new total
            $this->cartItemCalculatorService->updateCartItemTotals($cartItem);
            $this->repository->save($cartItem);
        } catch (Exception $e) {
            dd($e->getMessage());
        }

    }


    public function minusQuantity($id): void
    {
        $cartItem = $this->getOneById($id);

        if ($cartItem instanceof CartItem) {
            try {

                $quantity = $cartItem->getQuantity();

                // if quantity == 0 , delete the item
                ($quantity == 1) ? $this->repository->delete($cartItem) : $cartItem->setQuantity($quantity - 1);

                //calculate new total
                $this->cartItemCalculatorService->updateCartItemTotals($cartItem);
                $this->repository->save($cartItem);
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }

    }


}
