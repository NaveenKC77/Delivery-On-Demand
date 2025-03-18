<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordService
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function hashPassword(User $user, string $plainPassword): string
    {
        return $this->userPasswordHasher->hashPassword($user, $plainPassword);
    }
}
