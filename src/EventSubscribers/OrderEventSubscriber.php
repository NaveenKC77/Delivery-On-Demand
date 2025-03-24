<?php

namespace App\EventSubscribers;

use App\Entity\Order;
use App\Event\Events\OrderCancelledEvent;
use App\Event\Events\OrderConfirmedEvent;
use App\Event\Events\OrderPlacedEvent;
use App\Repository\ProductRepository;
use App\Services\NotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OrderEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private HttpClientInterface $client, private MailerInterface $mailer,private ProductRepository $productRepository,private NotificationService $notificationService)
    {
    }

    public function onOrderPlaced(OrderPlacedEvent $event): void
    {

        //reduce product stock
        $order = $event->getOrder();

        if ($order instanceof Order) {
            $this->reduceStock($order);

            $customer = $order->getCustomer()->getUser();

            $title = "Order ". $event->getAction();
    
            $content = "Order Placed . Our admin will look into it and confirm it . You can track your order status by clicking the 'View Details' button ";

            $orderId=$order->getId();
            $link = "/user/order/single/$orderId";

            $this->notificationService->newNotification($customer,$title,$content,$link);


        }
        //send notification to user

        // log in dynamo db
    }
    
    public function onOrderConfirmed(OrderConfirmedEvent $event): void{
        $order = $event->getOrder();

        if ($order instanceof Order) {

            // get user entity for the id
            $customer = $order->getCustomer()->getUser();

            // notification title , capitalise first letter of every word
            $title=ucwords("Order ". $event->getAction());

            $orderId=$order->getId();

            $content = "Order Confirmed. You can track you order now.";

            $link = "/user/order/single/$orderId";

            // create new notification
            $this->notificationService->newNotification($customer,$title,$content,$link);

        }
    }


    public function onOrderCancelled(OrderCancelledEvent $event): void{
        $order = $event->getOrder();

        if ($order instanceof Order) {
            $customer = $order->getCustomer()->getUser();

            $title="Order". $event->getAction();

            $orderId=$order->getId();
            $content = " Sorry Order Cancelled .";
            $link = "/user/order/single/$orderId";

            $this->notificationService->newNotification($customer,$title,$content,$link);

        }
    }
    public static function getSubscribedEvents()
    {
        return [
            OrderPlacedEvent::class => ['onOrderPlaced'],
            OrderConfirmedEvent::class=>['onOrderConfirmed'],
            OrderCancelledEvent::class=>['onOrderCancelled']
        ];
    }

    public function reduceStock(Order $order)
    {

        $orderItems = $order->getCartItems();

        foreach ($orderItems as $orderItem) {
            $product = $orderItem->getProduct();
            $quantity = $orderItem->getQuantity();
            $newStock = $product->getStock() - $quantity;
            $product->setStock($newStock);

           $this->productRepository->save($product);
        }
    }
}
