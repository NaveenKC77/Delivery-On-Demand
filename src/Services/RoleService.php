<?php

namespace App\Services;

use App\Entity\User;

class RoleService implements RoleServiceInterface
{
    public function assignRole(User $user, string $role): void
    {
        $user->setRoles([$role]);
    }
}
