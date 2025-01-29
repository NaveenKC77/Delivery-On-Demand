<?php

namespace App\Event\Events;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractUserEvent extends Event
{
    public function __construct(
        private User $user,
    ) {
    }

    protected const REGISTER = "Register";
    protected const VERIFIED = "Verify";
    protected const UPDATE = "Update";
    protected const DELETE = "Delete";
    /**
     * Summary of getAction
     * @return string
     *                return action type 'Create','Update' or 'Delete'
     */
    abstract protected function getAction(): string;

    public function getEntityType()
    {
        return 'User';
    }
    public function getUser(): User
    {
        return $this->user;
    }

}
