<?php

namespace App\Services;

use App\Services\CartCalculatorServiceInterface;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartRepositoryInterface;

/**
 * Handles Cart Business Logic i.e. adding and removing cartItem, and checking if item exists in the cart
 */
class CartService extends AbstractEntityService implements CartServiceInterface
{
    public function __construct(protected CartRepositoryInterface $repository, private CartItemCalculatorServiceInterface $cartItemCalculatorService,private CartCalculatorServiceInterface $cartCalculatorService)
    {
        parent::__construct(repository: $repository);
    }

    public function getEntityClass():string{
        return Cart::class;
    }

    
    public function getCartFromCustomerId(int $customerId): ?Cart
    {
        return $this->repository->findByCustomerId($customerId);
    }
 

    public function addCartItem(Cart $cart, CartItem $cartItem): void
    {

        $this->cartItemCalculatorService->updateCartItemTotals($cartItem);

        $cart->addCartItem($cartItem);

        $this->cartCalculatorService->updateCartTotals($cart);

        $this->repository->save($cart);

    }

   
    public function removeCartItem(Cart $cart, CartItem $cartItem): void
    {

        $cart->removeCartItem($cartItem);

        $this->cartCalculatorService->updateCartTotals($cart);

        $this->repository->save($cart);

    }

    public function checkItemExists(Cart $cart, CartItem $cartItem): bool
    {

      return $cart->getCartItems()->exists(fn($key,CartItem $ci)=>
        $ci->getProduct()->getId()=== $cartItem->getProduct()->getId()
    );

    }
}
