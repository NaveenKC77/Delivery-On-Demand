<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\HttpFoundation\Request;

class UserVerificationService implements UserVerificationServiceInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private VerifyEmailHelperInterface $verifyEmailHelper
    ) {
    }

    public function verifyEmail(Request $request): void
    {
        $userId = $request->query->get('id');
        $user = $this->userRepository->findOneById($userId);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        try {
            $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            throw new \Exception($e->getReason());
        }

        $user->setIsVerified(true);
        $this->userRepository->getEntityManager()->flush();
    }

    public function resendVerificationEmail(string $username): string
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        $signedUrl = $this->generateSignedUrl($user);

        return $signedUrl;

    }

    public function generateSignedUrl(User $user): string
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
}
