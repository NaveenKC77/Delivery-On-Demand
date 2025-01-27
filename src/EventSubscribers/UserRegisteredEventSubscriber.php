<?php

namespace App\EventSubscribers;

use App\Event\Events\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserRegisteredEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private HttpClientInterface $client, private MailerInterface $mailer)
    {
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $signedUrl = $event->getSignedUrl();
        $user = $event->getUser();


        // log in dynamo db


        // $data = ['message' => [
        //     'email' => $user->getEmail(),
        //     'mailBody' => $signedUrl,
        // ]];

        // $restApiUrl = getenv('MAIL_USER_API');

        // $response = $this->client->request(
        //     'POST',
        //     $restApiUrl,
        //     ['headers' => ['content-type' => 'application/json'], 'json' => $data]
        // );
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::class => ['onUserRegistered', -10],
        ];
    }
}
