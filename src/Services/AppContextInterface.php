<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\User;

interface AppContextInterface
{
    public function getCurrentUser(): User;

    public function getCurrentCustomer(): Customer;

    public function getCurrentCart(): Cart;
    public function getUnreadNotificationsCount(): int;
}
