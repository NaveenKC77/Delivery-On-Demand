<?php

namespace App\Controller;

use App\Services\DynamoDbService;
use App\Services\ProductService;
use App\Services\ServicesInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductLogsController extends AbstractLogsController
{
    public function __construct(private ProductService $productService, private DynamoDbService $dynamoDbService, private UserService $userService)
    {

        parent::__construct($this->dynamoDbService, $this->userService);
    }

    public function getEntityType(): string
    {
        return 'Product';
    }

    public function getRedirectRoute(): string
    {
        return 'product_logs';
    }

    public function getService(): ServicesInterface
    {
        return $this->productService;
    }

    /**
     * main page for all product logs
     */
    #[Route(path:"/admin/logs/product", name:"product_logs")]
    public function getAllProductLogs(Request $request): Response
    {

        $this->setTemplateName('admin/logs/product.html.twig');

        $itemId = $request->getSession()->get('productLogId', $this->getItemId());
        return parent::getAllLogs($request, $itemId);
    }

    /**
        * get logs by Entity-Action-Index
        * axtion is a string that starts with Capital Letter i.e Create,Update,Delete
        */
    #[Route(path:"/admin/logs/product/action/{action}", name:"product_logs_action")]
    public function sortProductLogsByAction(string $action, Request $request)
    {
        $request->getSession()->set('action', $action);

        return $this->redirectToRoute($this->getRedirectRoute());
    }

    #[Route(path:"/admin/logs/product/admin/{adminId}", name:"")]
    public function sortProductLogsByAdmin(int $adminId, Request $request)
    {
        return parent::sortLogsByAdmin($adminId, $request);
    }

    #[Route(path:"/admin/logs/product/timeInterval/{interval}", name:"product_logs_time")]
    public function sortProductLogsByTimeInterval(string $interval, Request $request)
    {
        return parent::sortLogsByTimeInterval($interval, $request);
    }

    #[Route(path:"/admin/logs/product/product/{id}", name:"product_logs_single")]
    public function sortProductLogsBySpecificItem(int $id, Request $request)
    {
        $request->getSession()->set("productLogId", $id);

        return $this->redirectToRoute($this->getRedirectRoute());

    }
}
