<?php

namespace App\Services;

class UserProfileService{


    public function __construct(private OrderService $orderService){}

    public function getOrders($customerId){
        return $this->orderService->getAllByCustomerId($customerId);
    }
}