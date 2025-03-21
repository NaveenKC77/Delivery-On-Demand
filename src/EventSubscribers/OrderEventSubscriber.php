<?php

namespace App\EventSubscribers;

use App\Entity\Order;
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

            $title = "Order Placed";
    
            $content = "Order Placed , Our admin will look into it , Order Number 11";

            $this->notificationService->newNotification($customer,$title,$content);


        }
        //send notification to user

       






        // log in dynamo db
    }

    public static function getSubscribedEvents()
    {
        return [
            OrderPlacedEvent::class => ['onOrderPlaced'],
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
