<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\OrderDetails;
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

    public function createOrder(Customer $customer, Cart $cart, OrderDetails $orderDetails)
    {

        $order = new Order();
        // Set the order details and subtotal
        $order->setOrderDetails($orderDetails);
        dd($cart);
        $order->setSubtotal($cart->getTotal());

        // Add cart items to the order
        foreach ($cart->getCartItems() as $item) {
            $order->addCartItem($item);
        }

        dd($order);

        // Automatically calculate tax and total based on subtotal
        $order->setTax(); 
        $order->setTotal($order->calculateTotal());
        $order->setCustomer($customer);
        // Reset the cart
        $cart->resetCart();
        $this->add($order);
        return $order;




    }
}
