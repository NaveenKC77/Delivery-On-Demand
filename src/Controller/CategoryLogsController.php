<?php

namespace App\Controller;

use App\Services\DynamoDbService;
use App\Services\CategoryService;
use App\Services\ServicesInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryLogsController extends AbstractLogsController
{
    public function __construct(private CategoryService $categoryService, private DynamoDbService $dynamoDbService, private UserService $userService)
    {

        parent::__construct($this->dynamoDbService, $this->userService);
    }

    public function getEntityType(): string
    {
        return 'Category';
    }

    public function getRedirectRoute(): string
    {
        return 'category_logs';
    }

    public function getService(): ServicesInterface
    {
        return $this->categoryService;
    }

    /**
     * main page for all category logs
     */
    #[Route(path:"/admin/logs/category", name:"category_logs")]
    public function getAllCategoryLogs(Request $request): Response
    {
        $this->setTemplateName('admin/logs/category.html.twig');
        // get filter for specific category id
        $itemId = $request->getSession()->get('categoryLogId', $this->getItemId());
        return parent::getAllLogs($request, $itemId);
    }

    #[Route(path:"/admin/logs/category/action/{action}", name:"category_logs_action")]
    public function sortCategoryLogsByAction(string $action, Request $request)
    {
        $request->getSession()->set('action', $action);

        return $this->redirectToRoute($this->getRedirectRoute());
    }

    #[Route(path:"/admin/logs/category/admin/{adminId}", name:"")]
    public function sortCategoryLogsByAdmin(int $adminId, Request $request)
    {
        return parent::sortLogsByAdmin($adminId, $request);
    }

    #[Route(path:"/admin/logs/category/timeInterval/{interval}", name:"category_logs_time")]
    public function sortCategoryLogsByTimeInterval(string $interval, Request $request)
    {
        return parent::sortLogsByTimeInterval($interval, $request);
    }

    #[Route(path:"/admin/logs/category/category/{id}", name:"category_logs_single")]
    public function sortCategoryLogsBySpecificItem(int $id, Request $request)
    {
        // set filter for specific categoryId
        $request->getSession()->set("categoryLogId", $id);

        return $this->redirectToRoute($this->getRedirectRoute());

    }
}
