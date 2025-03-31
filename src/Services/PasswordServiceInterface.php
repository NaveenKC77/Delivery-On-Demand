<?php

namespace App\Services;

use App\Entity\User;

interface PasswordServiceInterface
{
    /**
     * Hashes a plain text password for the given user
     *
     * @param User $user The user entity the password belongs to
     * @param string $plainPassword The unhashed password
     * @return string The hashed password
     */
    public function hashPassword(User $user, string $plainPassword): string;
}
