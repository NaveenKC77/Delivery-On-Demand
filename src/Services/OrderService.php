<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Factory\OrderFactory;
use App\Repository\OrderRepository;

class OrderService
{
    public function __construct(private OrderRepository $orderRepository, private OrderCalculatorService $orderCalculatorService, private OrderFactory $orderFactory)
    {
    }

    public function add($order)
    {
        $this->orderRepository->getEntityManager()->persist($order);
        $this->orderRepository->getEntityManager()->flush();
    }

    public function createOrder(Customer $customer, Cart $cart, OrderDetails $orderDetails)
    {

        //create order
        $order = $this->orderFactory->createOrder($customer, $cart, $orderDetails);

        //save order in Db
        $this->add($order);

        //   reset cart
        $cart->resetCart();

        return $order;


    }
}
