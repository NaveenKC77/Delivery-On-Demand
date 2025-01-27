<?php

namespace App\EventSubscribers;

use App\Entity\User;
use EmailNotVerifiedException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class VerificationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private RouterInterface $router, private Security $security)
    {
    }

    /**
     * Summary of onCheckPassport
     * checks if user is verified
     * @param \Symfony\Component\Security\Http\Event\CheckPassportEvent $event
     * @throws \Exception
     * @throws \App\EmailNotVerifiedException
     * @return void
     */
    public function onCheckPassport(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();

        /**
         * @var \User user
         */
        $user = $passport->getUser();

        if (!$user instanceof User) {
            throw new \Exception('Wrong type of User passed');
        }

        if (!$user->getIsVerified()) {
            throw new \App\EmailNotVerifiedException();
        }
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $exception = $event->getException();

        if (!$exception instanceof \App\EmailNotVerifiedException) {
            return;
        }

        $response = new RedirectResponse(
            $this->router->generate(
                'app_verify_resend_email',
                ['username' => $event->getPassport()->getUser()->getUserIdentifier()]
            )
        );

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
            LoginFailureEvent::class => ['onLoginFailure'],
        ];
    }
}
