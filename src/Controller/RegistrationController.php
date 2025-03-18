<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\Events\UserRegisteredEvent;
use App\Form\CustomerRegistrationFormType;
use App\Form\EmployeeRegistrationFormType;
use App\Services\UserRegistrationService;
use App\Services\UserVerificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private UserRegistrationService $userRegistrationService,
        private UserVerificationService $userVerificationService
    ) {
    }

    #[Route('/register/customer', name: 'app_register')]
    public function register(
        Request $request,
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_main');
        }

        $user = new User();
        $form = $this->createForm(CustomerRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Register customer
                $this->userRegistrationService->registerCustomer($user, $form);

                // Generate signed URL for email verification
                $signedUrl = $this->userVerificationService->generateSignedUrl($user);

                // Dispatch the UserRegisteredEvent
                $event = new UserRegisteredEvent($signedUrl, $user);
                $this->eventDispatcher->dispatch($event, UserRegisteredEvent::class);

                $this->addFlash('success', 'Registration successful! Confirm your email at: ' . $signedUrl);

                return $this->redirectToRoute('app_login');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Registration failed: ' . $e->getMessage());
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/employee', name: 'app_register_employee')]
    public function registerEmployee(
        Request $request,
    ): Response {
        // Check if the user has ROLE_ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have permission to access this page.');
        }

        $user = new User();
        $form = $this->createForm(EmployeeRegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Register employee
                $this->userRegistrationService->registerEmployee($user, $form);

                // Generate signed URL for email verification
                $signedUrl = $this->userVerificationService->generateSignedUrl($user);

                // Dispatch the UserRegisteredEvent
                $event = new UserRegisteredEvent($signedUrl, $user);
                $this->eventDispatcher->dispatch($event, UserRegisteredEvent::class);

                $this->addFlash('success', 'Employee registered successfully.');

                return $this->redirectToRoute('app_admin');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Employee registration failed: ' . $e->getMessage());
            }
        }

        return $this->render('registration/employee.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request)
    {
        try {
            $this->userVerificationService->verifyEmail($request);
            $this->addFlash('success', 'Verified Successfully! You can now log in.');
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_register');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/verify/resend/{username}', name: 'app_verify_resend_email')]
    public function resendVerifyEmail($username, Request $request)
    {
        try {
            // Return signedUrl for resending
            $signedUrl = $this->userVerificationService->resendVerificationEmail($username);

            $this->addFlash('success', 'Verification email sent. Confirm your email at: ' . $signedUrl);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to resend verification email: ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_login');
    }
}
