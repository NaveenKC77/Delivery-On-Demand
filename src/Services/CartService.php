<?php

namespace App\Services;

use App\Repository\CartRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class CartService implements ServicesInterface
{

    public function __construct(private CartRepository $cartRepository, private EntityManagerInterface $em) {}
    function add($entity): void {}
    function delete($entity) {}
    function edit($entity) {}
    function getAll(): array
    {
        return [];
    }
    function getOneById(int $id)
    {
        return $this->cartRepository->findOneById($id);
    }

    function returnCardProperties(): array
    {
        return [];
    }
    public function processUpload($imagePath, $uploadDir): string
    {
        return '';
    }
    public function addCartItem($cartItem)
    {
        $cart = $this->cartRepository->findOneById($cartItem->getCart()->getId());
        $cart->addCartItem($cartItem);
        $this->em->persist($cart);
        $this->em->flush();
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
