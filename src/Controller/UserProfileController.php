<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CustomerRegistrationFormType;
use App\Form\ProductFormType;
use App\Services\PhoneNumberService;
use App\Services\UserProfileService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserProfileController extends AbstractController{

    use AppControllerTrait;

    public function __construct(private UserProfileService $userProfileService,private PhoneNumberService $phoneNumberService){}

    public function getService(){
        return $this->userProfileService;
    }

    public function getFormType(): string{
        return CustomerRegistrationFormType::class;
    }

    #[Route('/user/profile',name:'app_profile')]
    public function profile(): Response{
        
        $user = $this->getUser();

        if($user instanceof User && $user->getCustomer()){
        $customerId = $user->getCustomer()->getId();
    }

    $orders = $this->userProfileService->getOrders($customerId);

    $this->setTemplateName('/user/profile/profile.html.twig');
    $this->setTemplateData(['user'=>$user,'orders'=>array_slice($orders,0,4)]);
    return $this->read();
    }

    #[Route('/user/profile/edit',name:'app_edit_profile')]

    public function editProfile(Request $request){

        $user = $this->getUser();

        if($user instanceof User && $user->getCustomer()){

            $form = $this->createForm($this->getFormType(),$user);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $editedUser = $form->getData();

                try{
                    
                $phoneNumber = $form->get('phoneNumber')->getData();

                // Normalize and validate the phone number
                $normalizedPhoneNumber = $this->phoneNumberService->normalizePhoneNumber($phoneNumber);
        
            
                if ($normalizedPhoneNumber === null) {
                    $this->addFlash('error', 'Invalid phone number.');
                    return $this->redirectToRoute('app_register');
                }
                
                $editedUser->setPhoneNumber($normalizedPhoneNumber);

                if($this->userProfileService->editProfile($editedUser)){
                    $this->addFlash('success','Profile Edited Successfully');
                return $this->redirectToRoute('app_profile');
                }

                $this->addFlash('error','Something Went Wrong');
                }

                catch(Exception $e){
                    $this->addFlash('error',$e->getMessage());
                    return $this->redirectToRoute('app_edit_profile');
                }

                
            }

            $this->setTemplateName('/user/profile/edit.html.twig');
            $this->setTemplateData(['user'=>$user,'form'=> $form->createView()]);

           return  $this->read();

        }
    }
}