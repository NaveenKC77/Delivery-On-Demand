<?php

namespace App\Event\Events;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractUserEvent extends Event implements AppLoggerEventInterface
{
    public function __construct(
        private User $user,
    ) {
    }

    // event typ e : create , delete , update
    protected const EVENT_NAME = "";

    public function getAction(): string
    {
        return static::EVENT_NAME;
    }
    public function getEntityType(): string
    {
        return 'User';
    }
    public function getUser(): User
    {
        return $this->user;
    }

}
