<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

interface UserRegistrationServiceInterface
{
    /**
     * Registers a new customer user
     *
     * @param User $user The user entity to register
     * @param Form $form The registration form containing plainPassword
     * @throws CustomUserMessageAuthenticationException When registration fails
     */
    public function registerCustomer(User $user, Form $form): void;

    /**
     * Registers a new employee user
     *
     * @param User $user The user entity to register
     * @param Form $form The registration form containing plainPassword
     * @throws CustomUserMessageAuthenticationException When registration fails
     */
    public function registerEmployee(User $user, Form $form): void;
}