<?php

namespace App\EventSubscribers;

use App\Entity\EntityInterface;
use App\Event\Events\CategoryCreatedEvent;
use App\Event\Events\CategoryDeletedEvent;
use App\Event\Events\CategoryUpdatedEvent;
use App\Event\Events\ProductCreatedEvent;
use App\Event\Events\ProductDeletedEvent;
use App\Event\Events\ProductUpdatedEvent;
use App\Event\Events\UserRegisteredEvent;
use App\Services\DynamoDbService;
use Aws\Exception\AwsException;
use Ramsey\Uuid\Guid\Guid;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\User;

class LoggerEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private MailerInterface $mailer,
        private DynamoDbService $dynamoDb,
        private Security $security,
    ) {
    }


    public function getAdmin()
    {

        // Get the currently authenticated user
        $user = $this->security->getUser();

        // Ensure the returned user is of the expected type or null
        if (!$user instanceof User) {
            return null;
        }

        return $user;

    }


    public function onProductCreated(ProductCreatedEvent $event): void
    {
        // product
        $product = $event->getProduct();

        $this->createLog($product, $event->getEntityType(), $event->getAction());

    }

    public function onProductUpdated(ProductUpdatedEvent $event): void
    {
        $product = $event->getProduct();

        $this->createLog($product, $event->getEntityType(), $event->getAction());

    }

    public function onProductDeleted(ProductDeletedEvent $event): void
    {
        $product = $event->getProduct();

        $this->createLog($product, $event->getEntityType(), $event->getAction());

    }


    public function onCategoryCreated(CategoryCreatedEvent $event): void
    {

        $category = $event->getCategory();

        $this->createLog($category, $event->getEntityType(), $event->getAction());

    }

    public function onCategoryUpdated(CategoryUpdatedEvent $event): void
    {
        $category = $event->getCategory();

        $this->createLog($category, $event->getEntityType(), $event->getAction());

    }

    public function onCategoryDeleted(CategoryDeletedEvent $event): void
    {
        $category = $event->getCategory();

        $this->createLog($category, $event->getEntityType(), $event->getAction());

    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();

        $this->createUserLog($user, $event->getEntityType(), $event->getAction());

    }

    public function createLog(EntityInterface $entity, string $entityType, string $action)
    {
        $date = new \DateTimeImmutable();

        // get Authenticated Admin
        $admin = $this->getAdmin();
        $adminId = $this->getAdmin()->getId();

        // unique uuid for storing LogId in Dynamo
        $logId = Guid::uuid4()->toString();



        $item = [
            'PK' => [
                'S' => "USER#$adminId"
            ],
            'SK' => [
                'S' => "$entityType#$logId"
            ],
            'Entity' => [
                'S' => "$entityType"
            ],
            'EntityId' => [
                'N' => (string) $entity->getId()
            ],
            'AdminId' => [
                'S' => (string) $admin->getId()
            ],
            'Action' => [
                'S' => $action
            ],
            'Date' => [
                'S' => $date->format(\DateTime::ATOM)
            ],
            'Admin' => [
                    'S' => $admin->getUsername()
                ]
            ];

        //log in dynamo db

        try {
            $this->dynamoDb->putItem($item);

        } catch (AwsException $e) {
            dd($e->getMessage());
        }
    }

    public function createUserLog(EntityInterface $entity, string $entityType, string $action)
    {
        $date = new \DateTimeImmutable();

        // unique uuid for storing LogId in Dynamo
        $logId = Guid::uuid4()->toString();

        $item = [
            'PK' => [
                'S' => "USER#null"
            ],
            'SK' => [
                'S' => "$entityType#$logId"
            ],
            'Entity' => [
                'S' => $entityType
            ],
            'EntityId' => [
                'N' => (string)  $entity->getId()
            ],
            'AdminId' => [
                'S' => '0'
            ],
            'Action' => [
                'S' => $action
            ],
            'Date' => [
                'S' => $date->format(\DateTime::ATOM)
            ],
            'Admin' => [
                    'S' => 'none'
                ]
            ];

        //log in dynamo db

        try {
            $this->dynamoDb->putItem($item);

        } catch (AwsException $e) {
            dd($e->getMessage());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::class => ['onUserRegistered'],
            ProductCreatedEvent::class => ['onProductCreated'],
            ProductUpdatedEvent::class => ['onProductUpdated'],
            ProductDeletedEvent::class => ['onProductDeleted'],
            CategoryCreatedEvent::class => ['onCategoryCreated'],
            CategoryUpdatedEvent::class => ['onCategoryUpdated'],
            CategoryDeletedEvent::class => ['onCategoryDeleted'],
            UserRegisteredEvent::class => ['onUserRegistered'],


        ];
    }
}
