<?php


namespace App\EventSubscribers;

use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use App\Entity\User;
use EmailNotVerifiedException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class VerificationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private RouterInterface $router, private Security $security) {}
    public function onCheckPassport(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();

        /**
         * @var \User user
         */
        $user = $passport->getUser();

        if (!$user instanceof User) {
            throw new Exception('Wrong type of User passed');
        }

        if (!$user->getIsVerified()) {
            throw new EmailNotVerifiedException();
        }
    }

    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $exception = $event->getException();

        if (!$exception instanceof EmailNotVerifiedException) {
            return;
        }

        $response = new RedirectResponse($this->router->generate('app_verify_resend_email', ['username' => $event->getPassport()->getUser()->getUserIdentifier()]));


        $event->setResponse($response);
    }


    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
            LoginFailureEvent::class => ['onLoginFailure']

        ];
    }
}
