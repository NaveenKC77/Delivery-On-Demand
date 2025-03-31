<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\Customer;
use App\Entity\User;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;

class AppContextService implements AppContextInterface
{
    public function __construct(private Security $security, private NotificationServiceInterface $notificationService)
    {

    }

    public function getCurrentUser(): User
    {
        $user =  $this->security->getUser();

        if (!$user instanceof User) {
            throw new RuntimeException('User Not Found');
        }

        return $user;
    }

    public function getCurrentCustomer(): Customer
    {
        $user = $this->getCurrentUser();

        $customer = $user->getCustomer();

        if (!$customer instanceof Customer) {
            throw new RuntimeException('User has no customer profile');
        }

        return $customer;
    }

    public function getCurrentCart(): Cart
    {
        $customer = $this->getCurrentCustomer();

        $cart = $customer->getCart();

        if (!$cart instanceof Cart) {
            throw new RunTimeException('Cart not set');
        }

        return $cart;
    }


    public function getUnreadNotificationsCount(): int
    {
        return $this->notificationService->countUnReadNotification($this->getCurrentUser());
    }
}
