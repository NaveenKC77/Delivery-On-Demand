<?php

namespace App\Controller;

use App\Services\DynamoDbService;
use App\Services\ProductService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LogsController extends AbstractController
{
    private array $admins = [];
    private array $products = [];
    private const ENTITY_TYPE = "Product";
    private string $action = 'All';
    private int $adminId = 0;
    private string $timeInterval = 'All';
    private int $productId = 0;

    private const REDIRECT_ROUTE="product_logs";
    public function __construct(
        private DynamoDbService $dynamoDbService,
        private UserService $userService,
        private ProductService $productService
    ) {
        $this->admins = $this->userService->getAllAdmin();
        $this->products = $this->productService->getAll();
    }

    public function setAction(string $action): static{
        $this->action = $action;
        return $this;
    }

    public function getAction(): string{
        return $this->action;
    }

    public function setAdminId(int $adminId): static{
        $this->adminId = $adminId;
        return $this;
    }

    public function getAdminId(): int{
        return $this->adminId;
    }

    public function setTimeInterval(string $timeInterval): static{
        $this->timeInterval = $timeInterval;
        return $this;
    }
    public function getTimeInterval(): string{
        return $this->timeInterval;
    }

    public function setProductId(int $productId): static{
        $this->productId = $productId;
        return $this;
    }

    public function getProductId(): int{
        return $this->productId;
    }

    //dashboard
    #[Route(path:"/admin/logs", name:"logs_main")]
    public function index(Request $request)
    {
        
    }

    //Products
    #[Route(path:"/admin/logs/product", name:"product_logs")]
    public function allProductLogs(Request $request)
    {
    // Retrieve filters from the request
    $action = $request->getSession()->get('action', $this->getAction());
    $adminId = $request->getSession()->get('adminId', $this->getAdminId());
    $timeInterval = $request->getSession()->get('timeInterval', $this->getTimeInterval());
    $productId = $request->getSession()->get('productId', $this->getProductId());
    
    // Get product logs by entity type
    $productLogs = $this->dynamoDbService->getLogsByEntityType(self::ENTITY_TYPE);

    // Apply filters one by one

    // Filter by Action
    if ($action !== 'All') {
        $productLogs = array_filter($productLogs, function ($log) use ($action) {
            return $log->Action === $action;
        });
    }

    // Filter by AdminId
    if ($adminId !== 0) {
        $productLogs = array_filter($productLogs, function ($log) use ($adminId) {
            return $log->AdminId == $adminId;
        });
    }

    // Filter by Time Interval (e.g., last 24 hours, last week, last month)
    if ($timeInterval !== 'All') {
        $currentDateTime = new \DateTime('now', new \DateTimeZone('UTC')); // Current time in UTC
        $endDateString = $currentDateTime->format('Y-m-d\TH:i:s\Z'); // ISO 8601 format

        switch($timeInterval) {
            case 'day':
                $startDateString = (clone $currentDateTime)->modify('-24 hours')->format('Y-m-d\TH:i:s\Z');
                break;
            case 'week':
                $startDateString = (clone $currentDateTime)->modify('-7 days')->format('Y-m-d\TH:i:s\Z');
                break;
            case 'month':
                $startDateString = (clone $currentDateTime)->modify('-30 days')->format('Y-m-d\TH:i:s\Z');
                break;
            default:
                $startDateString = $endDateString; // No filtering
        }

        // Apply time interval filter
        $productLogs = array_filter($productLogs, function ($log) use ($startDateString, $endDateString) {
            $logDate = new \DateTime($log->Date);
            return $logDate >= new \DateTime($startDateString) && $logDate <= new \DateTime($endDateString);
        });
    }

    // Filter by ProductId
    if ($productId !== 0) {
        $productLogs = array_filter($productLogs, function ($log) use ($productId) {
            return $log->EntityId == $productId;
        });
    }

    // Sort by date, recent at the top
    usort($productLogs, function ($a, $b) {
        $dateA = new \DateTime($a->Date);
        $dateB = new \DateTime($b->Date);
        return $dateB <=> $dateA; // Sort by descending order (recent first)
    });

   
    // Return the filtered and sorted product logs
    return $this->render(
        'admin/logs/product.html.twig',
        [
            'productLogs' => $productLogs,
            'selectedOptions' => [
                'action' => $action,
                'admin' => $adminId,
                'timeInterval' => $timeInterval,
                'productId' => $productId,
            ],
            'admins' => $this->admins,
            'products' => $this->products,
        ]
    );
    }
    /**
     * get logs by Entity-Action-Index
     * axtion is a string that starts with Capital Letter i.e Create,Update,Delete
     */
    #[Route(path:"/admin/logs/product/action/{action}", name:"product_logs_action")]
    public function sortProductLogsByAction(string $action, Request $request)
    {
        $request->getSession()->set('action', $action);
        return $this->redirectToRoute(self::REDIRECT_ROUTE);
    }

    #[Route(path:"/admin/logs/product/admin/{adminId}", name:"")]
    public function sortProductLogsByAdmin(int $adminId, Request $request)
    {
       $request->getSession()->set('adminId',$adminId);
       return $this->redirectToRoute(self::REDIRECT_ROUTE);
    }

    #[Route(path:"/admin/logs/product/timeInterval/{interval}", name:"product_logs_time")]
    public function sortProductLogsByTimeInterval(string $interval, Request $request)
    {
   $request->getSession()->set("timeInterval",$interval);
    return $this->redirectToRoute(self::REDIRECT_ROUTE);
    }

    #[Route(path:"/admin/logs/product/product/{id}", name:"product_logs_single")]
    public function productLogsForSingleProduct(int $id, Request $request)
    {
        $request->getSession()->set("productId",$id);

        return $this->redirectToRoute(self::REDIRECT_ROUTE);

    }


}
