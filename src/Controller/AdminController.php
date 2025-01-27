<?php

namespace App\Controller;

use App\Services\DynamoDbService;
use App\Services\LogFilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    public function __construct(private DynamoDbService $dynamoDbService, private LogFilterService $logFilterService)
    {
    }

    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {

        // get all logs of product added
        $productsAdded = $this->dynamoDbService->getLogsByEntityAction('Product', 'Create');

        // chart data for products added in last [day,week,month]
        $productsAddedData = [
            count($this->logFilterService->filterLogsByTimeInterval('day', $productsAdded)),
            count($this->logFilterService->filterLogsByTimeInterval('week', $productsAdded)),
            count($this->logFilterService->filterLogsByTimeInterval('month', $productsAdded)),
        ];

        //get all logs for categories added
        $categoriesAdded = $this->dynamoDbService->getLogsByEntityAction('Category', 'Create');

        // chart data for categories added in last [day,week,month]
        $categoriesAddedData = [
            count($this->logFilterService->filterLogsByTimeInterval('day', $categoriesAdded)),
            count($this->logFilterService->filterLogsByTimeInterval('week', $categoriesAdded)),
            count($this->logFilterService->filterLogsByTimeInterval('month', $categoriesAdded)),
        ];

        $data = [
            'products' => $productsAddedData, // [last 24 hours, last week, last month]
            'categories' => $categoriesAddedData,
            'users' => [5,20,6]
        ];



        return $this->render('admin/dashboard.html.twig', [
            'data' => $data
        ]);

    }


}
