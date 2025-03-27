<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

class UserRegistrationService implements UserRegistrationServiceInterface
{
    public function __construct(
        private PasswordServiceInterface $passwordService,
        private RoleServiceInterface $roleService,
        private CartServiceInterface $cartService,
        private EntityManagerInterface $entityManager,
        private CartManagerInterface $cartManager
    ) {
    }

    public function registerCustomer(User $user, Form $form): void
    {
        /** @var string $plainPassword */
        $plainPassword = $form->get('plainPassword')->getData();

        try {
            // encode the plain password
            $user->setPassword($this->passwordService->hashPassword($user, $plainPassword));

            // set role
            $this->roleService->assignRole($user, 'ROLE_CUSTOMER');

            $this->cartManager->createCart($user);

            // save in db
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function registerEmployee(User $user, Form $form): void
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
