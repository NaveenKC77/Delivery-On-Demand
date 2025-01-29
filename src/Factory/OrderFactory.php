<?php

namespace App\Factory;

use App\Entity\Order;
use App\Entity\Customer;

class OrderFactory
{
    public function createOrder(Customer $customer): Order
    {
        $order = new Order();
        $order->setCustomer($customer);
        $order->setSubtotal(0);
        $order->setTax(0);
        $order->setShipping(0);
        $order->setTotal(0);

        return $order;
    }
}