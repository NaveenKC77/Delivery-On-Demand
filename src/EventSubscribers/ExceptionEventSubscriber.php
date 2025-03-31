<?php

namespace App\EventSubscribers;

use App\Exception\CartException;
use AWS\CRT\HTTP\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionEventSubscriber implements EventSubscriberInterface
{
    public function onExceptionRaised(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof CartException) {
            $response = new JsonResponse(
                ['error' => $exception->getMessage()],
                $exception->getCode()
            );
            $event->setResponse($response);
        }
    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onExceptionRaised'],
        ];
    }


}
