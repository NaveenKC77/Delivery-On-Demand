<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class UserRegistrationService
{
    public function __construct(
        private PasswordService $passwordService,
        private RoleService $roleService,
        private CartService $cartService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function registerCustomer(User $user, Form $form)
    {
        /** @var string $plainPassword */
        $plainPassword = $form->get('plainPassword')->getData();

        try {
            // encode the plain password
            $user->setPassword($this->passwordService->hashPassword($user, $plainPassword));

            // set role
            $this->roleService->assignRole($user, 'ROLE_CUSTOMER');

            // initialize cart
            $this->cartService->initializeCart($user);

            // save in db
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function registerEmployee(User $user, Form $form)
    {
        $plainPassword = $form->get('plainPassword')->getData();

        try {
            // encode the plain password
            $user->setPassword($this->passwordService->hashPassword($user, $plainPassword));
            $user->setRoles(['IS_EMPLOYEE']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }


}
