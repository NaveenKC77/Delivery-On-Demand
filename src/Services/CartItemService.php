<?php

namespace App\Services;

use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartItemService
{
    public function __construct(private CartItemRepository $cartItemRepository, private EntityManagerInterface $em)
    {
    }

    public function add($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
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
        $this->em->persist($cartItem);
        $this->em->flush();
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
        $this->em->persist($cartItem);
        $this->em->flush();
    }
}
