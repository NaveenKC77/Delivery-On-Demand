<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class UserRegistrationService
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private UserPasswordHasherInterface $userPasswordHasher,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function registerCustomer(User $user, Form $form)
    {
        /** @var string $plainPassword */
        $plainPassword = $form->get('plainPassword')->getData();

        try {
            // encode the plain password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));

            // set role
            $user->setRoles(['ROLE_CUSTOMER']);

            // initialize cart
            $cart = new Cart();
            $cart->setCustomer($user->getCustomer());

            // save in db
            $this->entityManager->persist($user);
            $this->entityManager->persist($cart);
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
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(['IS_EMPLOYEE']);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function generateSignedUrl(User $user)
    {
        // Verification
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'app_verify_email',
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $signedUrl = $signatureComponents->getSignedUrl();

        return $signedUrl;
    }

    public function verifyEmail()
    {
    }

    public function resendVerificationEmail()
    {
    }
}
