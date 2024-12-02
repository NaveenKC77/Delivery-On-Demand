<?php

namespace App\Services;

use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;

class CartItemService implements ServicesInterface
{

    public function __construct(private CartItemRepository $cartItemRepository, private EntityManagerInterface $em) {}
    function add($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
    function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
    function edit($entity) {}
    function getAll(): array
    {
        return [];
    }
    function getOneById(int $id)
    {
        return $this->cartItemRepository->findOneById($id);
    }

    function returnCardProperties(): array
    {
        return [];
    }
    public function processUpload($imagePath, $uploadDir): string
    {
        return '';
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

        if ($quantity == 1) {
            $this->delete($cartItem);
        } else {
            $cartItem->setQuantity($quantity - 1);
        }
        $this->em->persist($cartItem);
        $this->em->flush();
    }
}
