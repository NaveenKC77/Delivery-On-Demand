<?php

namespace App\Services;

use App\Entity\User;

interface UserProfileServiceInterface
{
    /**
     * Get all orders for a customer
     *
     * @param int $customerId The customer ID
     * @return array Array of Order entities
     */
    public function getOrders(int $customerId): array;

    /**
     * Edit a user's profile
     *
     * @param User $editedUser The updated User entity
     * @return bool True if successful, false on failure
     */
    public function editProfile(User $editedUser): bool;
}
