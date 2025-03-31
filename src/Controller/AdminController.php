<?php

namespace App\Controller;

use App\Services\AdminDashboardServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    public function __construct(private AdminDashboardServiceInterface $adminDashboardService)
    {
    }

    /**
     * Admin Dashboard Page
     * Gets Dashboard Data and renders the page
     * To access, User has to be admin
     */
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        //Get Dashboard Chart Data
        $data = $this->adminDashboardService->getDashboardData();

        return $this->render('admin/dashboard.html.twig', [
            'data' => $data
        ]);

    }

}
