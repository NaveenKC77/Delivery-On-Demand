<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\EntityInterface;
use App\Entity\User;
use App\Repository\CartRepositoryInterface;


class CartService extends AbstractEntityService
{
    public function __construct(public CartRepositoryInterface $repository, private CartItemService $cartItemService,private CartCalculatorService $cartCalculatorService)
    {
        parent::__construct(repository: $repository);
    }

    public function getAll():array{
        return $this->repository->findAll();
    }

    public function getAllQueryBuilder(): \Doctrine\ORM\QueryBuilder{
        return $this->repository->getAllQueryBuilder();
    }

    public function getOneById(int $id): EntityInterface
    {
        return $this->repository->find($id);
    }

    public function getCartFromCustomerId($customerId): Cart|null
    {
        return $this->repository->findByCustomerId($customerId);
    }

    public function initializeCart(User $user): void
    {
        $cart = new Cart();
        $cart->setCustomer($user->getCustomer());
        $this->save($cart);

    }

    public function addCartItem(Cart $cart, CartItem $cartItem): void
    {

        $this->cartItemService->resetCartItemNumbers($cartItem);

        $cart->addCartItem($cartItem);

        $this->resetCartNumbers($cart);

        $this->save($cart);

    }

    public function removeCartItem(Cart $cart, CartItem $cartItem): void
    {

        $cart->removeCartItem($cartItem);

        $this->resetCartNumbers($cart);

        $this->save($cart);

    }

    public function resetCartNumbers(Cart $cart): void
    {

        //calculate quantity and total
        $quantity = $this->cartCalculatorService->calculateQuantity($cart->getCartItems());
        $total = $this->cartCalculatorService->calculateTotal($cart->getCartItems());

        $cart->setTotal($total);
        $cart->setQuantity($quantity);

        $this->save($cart);
    }

    public function checkItemExists(Cart $cart, CartItem $cartItem): bool
    {

      return $cart->getCartItems()->exists(fn($key,CartItem $ci)=>
        $ci->getProduct()->getId()=== $cartItem->getProduct()->getId()
    );

    }
}
