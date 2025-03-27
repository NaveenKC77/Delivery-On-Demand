<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;

class UserProfileService
{
    public function __construct(private OrderService $orderService, private UserRepository $userRepository, private PhoneNumberService $phoneNumberService)
    {
    }

    public function getOrders($customerId)
    {
        return $this->orderService->getAllByCustomerId($customerId);
    }

    public function editProfile(User $editedUser)
    {
        try {
            $this->userRepository->save($editedUser);
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
}
