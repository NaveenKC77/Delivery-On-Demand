<?php

namespace App\Controller;

use App\Services\DynamoDbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    public function __construct(private DynamoDbService $dynamoDbService)
    {
    }
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {


        return $this->render('admin/dashboard.html.twig', [

        ]);
    }


    #[Route('/admin/logs/category', name:'category_logs')]
    public function categoryLogs(): Response
    {

        $categoryLogs = $this->dynamoDbService->getLogsByEntityType('Category');

        //sort array of objects by date , recent at top
        usort($categoryLogs, function ($a, $b) {

            //Date is stored as string in dynamoDb , hence converting to DateTime type
            $dateA = new \DateTime($a->Date);
            $dateB = new \DateTime($b->Date);

            return $dateA < $dateB;
        });
        return $this->render(
            'admin/logs/product.html.twig',
            ['categoryLogs' => $categoryLogs]
        );
    }
}
