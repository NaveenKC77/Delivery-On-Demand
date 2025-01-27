<?php

namespace App\EventSubscribers;

use App\Entity\Order;
use App\Event\Events\OrderPlacedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OrderEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private HttpClientInterface $client, private MailerInterface $mailer, private EntityManagerInterface $entityManager)
    {
    }

    public function onOrderPlaced(OrderPlacedEvent $event): void
    {

        //reduce product stock
        $order = $event->getOrder();

        if ($order instanceof Order) {
            $this->reduceStock($order);
        }
        //send mail

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

            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }
    }
}
