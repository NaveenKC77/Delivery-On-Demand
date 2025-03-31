<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

interface UserVerificationServiceInterface
{
    /**
     * Verifies a user's email address using the verification link
     *
     * @param Request $request The request containing verification parameters
     * @throws \Exception If user not found or verification fails
     */
    public function verifyEmail(Request $request): void;

    /**
     * Resends verification email to a user
     *
     * @param string $username The username to resend verification to
     * @return string The signed verification URL
     * @throws \Exception If user not found
     */
    public function resendVerificationEmail(string $username): string;

    /**
     * Generates a signed verification URL for a user
     *
     * @param User $user The user to generate URL for
     * @return string The signed verification URL
     */
    public function generateSignedUrl(User $user): string;
}
