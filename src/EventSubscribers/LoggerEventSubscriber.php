<?php

namespace App\EventSubscribers;

use App\Event\Events\CategoryCRUDEvent;
use App\Event\Events\ProductCRUDEvent;
use App\Event\Events\UserRegisteredEvent;
use App\Services\DynamoDbService;
use Aws\Exception\AwsException;
use Ramsey\Uuid\Guid\Guid;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Product;
use App\Entity\User;

class LoggerEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private MailerInterface $mailer,
        private DynamoDbService $dynamoDb,
        private Security $security
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
    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $signedUrl = $event->getSignedUrl();
        $user = $event->getUser();

        // log in dynamo db

    }

    public function onProductCRUD(ProductCRUDEvent $event): void
    {
        // product
        $product = $event->getProduct();

        // CRUD action type eg:Create
        $action = $event->getAction();
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
                'S' => "Product#$logId"
            ],
            'Entity' => [
                'S' => 'Product'
            ],
            'EntityId' => [
                'N' => $product->getId()
            ],
            'AdminId' => [
                'S' => $admin->getId()
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


    public function onCategoryCRUD(CategoryCRUDEvent $event): void
    {
        // category
        $category = $event->getCategory();

        // CRUD action type eg:Create
        $action = $event->getAction();
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
                'S' => "Category#$logId"
            ],
            'Entity' => [
                'S' => 'Category'
            ],
            'EntityId' => [
                'N' => $category->getId()
            ],
            'AdminId' => [
                'S' => $admin->getId()
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

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::class => ['onUserRegistered'],
            ProductCRUDEvent::class => ['onProductCRUD',-10],
            CategoryCRUDEvent::class => ['onCategoryCRUD'],

        ];
    }
}
