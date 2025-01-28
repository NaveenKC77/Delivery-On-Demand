<?php

namespace App\Controller;

use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Summary of MainController
 * Controls homepage and its functionalities
 */
class MainController extends AbstractController
{
    public function __construct(private ProductService $productService){}
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        $featuredProducts = $this->productService->getFeaturedProducts();
        return $this->render('main/homepage.html.twig', [
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
