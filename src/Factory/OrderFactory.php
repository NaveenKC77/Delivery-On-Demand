<?php

namespace App\Factory;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Customer;
use App\Entity\OrderDetails;
use App\Services\OrderCalculatorService;

class OrderFactory
{
    public function __construct(private OrderCalculatorService $orderCalculatorService)
    {
    }
    public function createOrder(Customer $customer, Cart $cart, OrderDetails $orderDetails): Order
    {
        $order = new Order();
        // Set the order details and subtotal
        $order->setOrderDetails($orderDetails);

        $order->setSubtotal($cart->getTotal());

        $order->setCustomer($customer);

    
        // Add cart items to the order
        foreach ($cart->getCartItems() as $item) {
            $order->addCartItem($item);
        }

        // Automatically calculate tax and total based on subtotal .. assuming taxRate=10
        $tax = $this->orderCalculatorService->calculateTax($order->getSubtotal(), 10);
        $order->setTax($tax);

        // calculate shipping
        $order->setShipping($this->orderCalculatorService->calculateShipping());


        // calculate total
        $order->setTotal($this->orderCalculatorService->calculateTotal($order->getSubtotal(), $order->getTax(), $order->getShipping()));

        return $order;
    }
}
