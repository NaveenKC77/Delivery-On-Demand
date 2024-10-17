<?php

namespace App\Controller;

use App\Services\CategoryService;
use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    public function __construct(private ProductService $productService, private CategoryService $categoryService)
    {
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $productCardInfo = $this->productService->returnCardProperties();
        $categoryCardInfo = $this->categoryService->returnCardProperties();

        return $this->render(
            'admin/dashboard.html.twig',
            [
                'productCardInfo' => $productCardInfo, 'categoryCardInfo' => $categoryCardInfo,
            ]
        );
    }
}
