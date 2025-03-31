<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Factory\OrderFactory;
use App\Repository\OrderRepository;
use Doctrine\ORM\QueryBuilder;

class OrderService extends AbstractEntityService implements OrderServiceInterface
{
    public function __construct(private OrderRepository $repository, private OrderCalculatorService $orderCalculatorService, private OrderFactory $orderFactory)
    {
        parent::__construct($repository);
    }

    public function add($order): void
    {

        $this->repository->getEntityManager()->persist($order);
        $this->repository->getEntityManager()->flush();
    }

    protected function getEntityClass(): string
    {
        return Order::class;
    }

    /**
     * Get order By Entity
     * Ensures type safety
     * @return \App\Entity\Order
     */
    private function getOrder(int $id): Order
    {
        return $this->getOneById($id);
    }
    public function createOrder(Customer $customer, Cart $cart, OrderDetails $orderDetails): Order
    {

        //create order
        $order = $this->orderFactory->createOrder($customer, $cart, $orderDetails);

        //save order in Db
        $this->add($order);


        // explicitly persist the cart items
        foreach ($order->getCartItems() as $cartItem) {
            $this->repository->getEntityManager()->persist($cartItem);
        }
        //   reset cart
        $cart->resetCart();

        return $order;


    }


    public function getAllByCustomerId($customerId): array
    {
        return $this->repository->findOrdersByUserQueryBuilder($customerId)->getQuery()->getResult();
    }

    public function getAllByCustomerIdQueryBuilder($customerId): QueryBuilder
    {
        return $this->repository->findOrdersByUserQueryBuilder($customerId);
    }
}
