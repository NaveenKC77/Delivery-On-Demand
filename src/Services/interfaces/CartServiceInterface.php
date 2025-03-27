<?php

namespace App\Services;

use App\Entity\Cart;
interface CartServiceInterface extends EntityServicesInterface
{
    public function getCartFromCustomerId(int $customerId): ?Cart;  
}
