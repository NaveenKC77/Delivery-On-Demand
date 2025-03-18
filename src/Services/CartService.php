<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartRepository;
use App\Entity\User;

class CartService
{
    public function __construct(private CartRepository $cartRepository, private CartCalculatorService $cartCalculatorService, private CartItemService $cartItemService)
    {
    }

    public function getOneById(int $id): Cart | null
    {
        return $this->cartRepository->findOneById($id);
    }

    public function getCartFromCustomerId($customerId): Cart|null
    {
        return $this->cartRepository->findOneByCustomerId($customerId);
    }

    public function initializeCart(User $user): void
    {
        $cart = new Cart();
        $cart->setCustomer($user->getCustomer());
        $this->cartRepository->save($cart);

    }

    public function addCartItem(Cart $cart, CartItem $cartItem): void
    {

        $this->cartItemService->resetCartItemNumbers($cartItem);

        $cart->addCartItem($cartItem);

        $this->resetCartNumbers($cart);

        $this->cartRepository->save($cart);

    }

    public function removeCartItem(Cart $cart, CartItem $cartItem): void
    {

        $cart->removeCartItem($cartItem);

        $this->resetCartNumbers($cart);

        $this->cartRepository->save($cart);

    }

    public function resetCartNumbers(Cart $cart): void
    {

        //calculate quantity and total
        $quantity = $this->cartCalculatorService->calculateQuantity($cart->getCartItems());
        $total = $this->cartCalculatorService->calculateTotal($cart->getCartItems());

        $cart->setTotal($total);
        $cart->setQuantity($quantity);

        $this->cartRepository->save($cart);
    }

    public function checkItemExists(Cart $cart, CartItem $cartItem): bool
    {

        $cartItems = $cart->getCartItems()->toArray();

        $itemIdArray = array_map(function (CartItem $cartItem) {
            return $cartItem->getProduct()->getId();
        }, $cartItems);

        return in_array($cartItem->getProduct()->getId(), $itemIdArray);

    }
}
