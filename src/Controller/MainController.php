<?php

namespace App\Controller;

use App\Services\AppContextInterface;
use App\Services\ProductServiceInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Summary of MainController
 * Controls homepage and its functionalities
 */
class MainController extends AbstractController
{
    public function __construct(private ProductServiceInterface $productService, private LoggerInterface $logger, private AppContextInterface $appContext)
    {
    }
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        try{
            $unreadNotidicationsCount = $this->appContext->getUnreadNotificationsCount();
    }
    catch(Exception $e){
        $unreadNotidicationsCount = 0;
    }
       
        $featuredProducts = $this->productService->getFeaturedProducts();
        return $this->render('main/homepage.html.twig', [
            'featuredProducts' => $featuredProducts,
            'unreadNotificationsCount' => $unreadNotidicationsCount
        ]);
    }
}
