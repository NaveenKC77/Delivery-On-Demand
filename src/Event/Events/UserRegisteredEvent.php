<?php

namespace App\Event\Events;

use App\Entity\User;

use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends AbstractUserEvent
{
    public function __construct(
        private string $signedUrl,
        private User $user,
    ) {
    }

    public function getSignedUrl(): string
    {
        return $this->signedUrl;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAction(): string{
        return 'Register';
    }
}
