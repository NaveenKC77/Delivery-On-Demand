<?php

namespace App\Services;

use App\Entity\User;

interface RoleServiceInterface
{
    /**
     * Assigns a role to a user (replaces any existing roles)
     *
     * @param User $user The user entity to modify
     * @param string $role The role to assign (e.g., 'ROLE_CUSTOMER')
     */
    public function assignRole(User $user, string $role): void;
}
