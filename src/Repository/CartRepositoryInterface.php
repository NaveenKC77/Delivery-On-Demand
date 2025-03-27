<?php

namespace App\Repository;

use App\Entity\Cart;

interface CartRepositoryInterface extends EntityRepositoryInterface
{
    public function findByCustomerId(int $customerId): ?Cart;
}
