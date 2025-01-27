<?php

namespace App\Services;

use App\Repository\OrderRepository;

class OrderService
{
    public function __construct(private OrderRepository $orderRepository)
    {
    }

    public function add($order)
    {
        $this->orderRepository->getEntityManager()->persist($order);
        $this->orderRepository->getEntityManager()->flush();
    }
}
