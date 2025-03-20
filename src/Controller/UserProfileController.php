<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProductFormType;
use App\Services\UserProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserProfileController extends AbstractController{

    use AppControllerTrait;

    public function __construct(private UserProfileService $userProfileService){}

    public function getService(){
        return $this->userProfileService;
    }

    public function getFormType(): string{
        return ProductFormType::class;
    }

    #[Route('/profile',name:'app_profile')]
    public function profile(): Response{
        
        $user = $this->getUser();

        if($user instanceof User && $user->getCustomer()){
        $customerId = $user->getCustomer()->getId();
    }

    $orders = $this->userProfileService->getOrders($customerId);

    $this->setTemplateName('/user/profile.html.twig');
    $this->setTemplateData(['user'=>$user,'orders'=>array_slice($orders,0,4)]);
    return $this->read();
    }
}