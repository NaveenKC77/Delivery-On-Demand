<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderDetails;

interface OrderServiceInterface extends EntityServicesInterface
{
    /**
     * Persists an order entity
     */
    public function add(Order $order): void;

    /**
     * Creates a new order from customer, cart and order details
     */
    public function createOrder(Customer $customer, Cart $cart, OrderDetails $orderDetails): Order;

    /**
     * Gets all orders for a customer ID
     * @return Order[]
     */
    public function getAllByCustomerId(int $customerId): array;

    /**
     * Gets query builder for customer orders
     */
    public function getAllByCustomerIdQueryBuilder(int $customerId);
}
