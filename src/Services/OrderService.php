<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Factory\OrderFactory;
use App\Repository\OrderRepository;
use Doctrine\ORM\QueryBuilder;

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


         // explicitly persist the cart items
    foreach ($order->getCartItems() as $cartItem) {
        $this->orderRepository->getEntityManager()->persist($cartItem);
    }
        //   reset cart
        $cart->resetCart();

        return $order;


    }

    public function getAll(): array{
        return $this->orderRepository->findAll();
    }

    public function getAllQueryBuilder(): QueryBuilder{
        return $this->orderRepository->getAllQueryBuilder();
    }

    public function getOneById(int $id):Order | null{
        return $this->orderRepository->findOneById($id);
    }

    public function getAllByCustomerId($customerId){
        return $this->orderRepository->findOrdersByUserQueryBuilder($customerId)->getQuery()->getResult();
    }

    public function getAllByCustomerIdQueryBuilder($customerId){
        return $this->orderRepository->findOrdersByUserQueryBuilder($customerId);
    }
}
