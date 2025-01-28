<?php

namespace App\Controller;

use App\Services\AdminDashboardService;
use App\Services\LoggerService;
use App\Services\LogFilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    public function __construct(private AdminDashboardService $adminDashboardService)
    {
    }

    /**
     * admin dashboard page
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
