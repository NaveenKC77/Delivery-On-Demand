<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\Events\UserRegisteredEvent;
use App\Form\CustomerRegistrationFormType;
use App\Form\EmployeeRegistrationFormType;
use App\Repository\UserRepository;
use App\Services\UserRegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private UserRepository $userRepository,
        private EventDispatcherInterface $eventDispatcher,
        private UserRegistrationService $userRegistrationService,
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
            $this->userRegistrationService->registerCustomer($user, $form);
            $signedUrl = $this->userRegistrationService->generateSignedUrl($user);
            $this->addFlash('success', 'Confirm your email at :' . $signedUrl);

            $event = new UserRegisteredEvent($signedUrl, $user);
            $this->eventDispatcher->dispatch($event, UserRegisteredEvent::class);

            return $this->redirectToRoute('app_login');
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
            /* @var string $plainPassword */

            $this->userRegistrationService->registerEmployee($user, $form);
            $this->addFlash('success', 'You have successfully registered an employee');

            $signedUrl = $this->userRegistrationService->generateSignedUrl($user);
            // do anything else you need here, like send an email

            $event = new UserRegisteredEvent($signedUrl, $user);
            $this->eventDispatcher->dispatch($event, UserRegisteredEvent::class);

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('registration/employee.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request)
    {
        $user = $this->userRepository->findOneById($request->query->get('id'));

        if (!$user) {
            throw $this->createNotFoundException();
        }
        try {
            $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());

            return $this->redirectToRoute('app_register');
        }

        $user->setIsVerified(true);
        $this->userRepository->getEntityManager()->flush();
        $this->addFlash('success', 'Verified Successfully ! You can now login.');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/verify/resend/{username}', name: 'app_verify_resend_email')]
    public function resendVerifyEmail($username, Request $request)
    {
        $user = $this->userRepository->findOneBy(['username' => $username]);

        $signedUrl = $this->userRegistrationService->generateSignedUrl($user);

        $this->addFlash('success', 'Confirm your email at :' . $signedUrl);

        return $this->redirectToRoute('app_main');
    }
}
