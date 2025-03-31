<?php

namespace App\Services;

use App\Entity\User;
use Exception;

class UserProfileService implements UserProfileServiceInterface
{
    public function __construct(private OrderServiceInterface $orderService, private PhoneNumberServiceInterface $phoneNumberService, private UserServiceInterface $userService)
    {
    }

    public function getOrders($customerId): array
    {
        return $this->orderService->getAllByCustomerId($customerId);
    }

    public function editProfile(User $editedUser): bool
    {
        try {
            $this->userService->save($editedUser);
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
}
