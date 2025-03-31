<?php

namespace App\Controller;

use App\Enum\ActiveSidenav;
use App\Form\CustomerRegistrationFormType;
use App\Services\AppContextInterface;
use App\Services\PhoneNumberServiceInterface;
use App\Services\UserProfileServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserProfileController extends AbstractReadController
{
    public function __construct(private UserProfileServiceInterface $userProfileService, private PhoneNumberServiceInterface $phoneNumberService, private AppContextInterface $appContext)
    {
    }

    public function getService(): UserProfileServiceInterface
    {
        return $this->userProfileService;
    }

    public function getFormType(): string
    {
        return CustomerRegistrationFormType::class;
    }

    #[Route('/user/profile', name:'app_profile')]
    public function profile(): Response
    {
        $user = $this->appContext->getCurrentUser();
        $customerId = $this->appContext->getCurrentCustomer()->getId();
        

        $orders = $this->userProfileService->getOrders($customerId);

        $this->setTemplateName('/user/profile/profile.html.twig');
        $this->setTemplateData(['user' => $user,'orders' => array_slice($orders, 0, 4),'active' => ActiveSidenav::PROFILE]);
        return $this->read();
    }

    #[Route('/user/profile/edit', name:'app_edit_profile')]

    public function editProfile(Request $request): RedirectResponse|Response
    {

        $user = $this->appContext->getCurrentUser();
        $form = $this->createForm($this->getFormType(), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $editedUser = $form->getData();

            try {

                $phoneNumber = $form->get('phoneNumber')->getData();

                // Normalize and validate the phone number
                $normalizedPhoneNumber = $this->phoneNumberService->normalizePhoneNumber($phoneNumber);


                if ($normalizedPhoneNumber === null) {
                    $this->addFlash('error', 'Invalid phone number.');
                    return $this->redirectToRoute('app_register');
                }

                $editedUser->setPhoneNumber($normalizedPhoneNumber);

                if ($this->userProfileService->editProfile($editedUser)) {
                    $this->addFlash('success', 'Profile Edited Successfully');
                    return $this->redirectToRoute('app_profile');
                }

                $this->addFlash('error', 'Something Went Wrong');
            } catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('app_edit_profile');
            }


        }

        $this->setTemplateName('/user/profile/edit.html.twig');
        $this->setTemplateData(['user' => $user,'form' => $form->createView(),'active' => ActiveSidenav::PROFILE]);

        return  $this->read();


    }
}
