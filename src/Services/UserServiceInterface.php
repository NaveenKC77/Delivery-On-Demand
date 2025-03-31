<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

interface UserServiceInterface extends EntityServicesInterface
{
    /**
     * Get QueryBuilder for all verified users
     */
    public function getAllVerifiedQueryBuilder(): QueryBuilder;

    /**
     * Delete a user by ID
     */
    public function deleteUser(mixed $id): void;

    /**
     * Get all admin users with an additional "All Admins" option
     *
     * @return array Array of admin users with 'id' and 'username' keys,
     *               including a first entry for "All Admins" (id=0)
     */
    public function getAllAdmin(): array;

    /**
     * Get logged in user
     * By implementing Security Class
     * @return User
     */
    public function getLoggedInUser(): User;
}
