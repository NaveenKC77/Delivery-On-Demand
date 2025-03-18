<?php

namespace App\Services;

use App\Entity\User;

class RoleService
{
    public function assignRole(User $user, string $role): void
    {
        $user->setRoles([$role]);
    }
}
