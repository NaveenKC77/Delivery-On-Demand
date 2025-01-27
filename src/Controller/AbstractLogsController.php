<?php

namespace App\Controller;

use App\Services\DynamoDbService;
use App\Services\ProductService;
use App\Services\ServicesInterface;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

abstract class AbstractLogsController extends AbstractController
{
    /**
     * Summary of admins
     * @var array
     *            list of admins in the system
     */
    private array $admins = [];

    /**
     * Summary of items
     * @var array
     *            all the items to be used for specific log sort
     */
    private array $items = [];

    /**
     * Summary of templateName
     * @var string
     *             twig template
     */
    private string $templateName;

    /**
     * Summary of action
     * @var string
     *             sort logs : Create, Update , Delete
     */
    private string $action = 'All';

    /**
     * Summary of adminId
     * @var int
     *          Selected admin  for sorting logs
     */
    private int $adminId = 0;

    /**
     * Summary of timeInterval
     * @var string
     *             to sort logs for last 24hrs, 7 days etc.
     */
    private string $timeInterval = 'All';

    /**
     * Summary of itemId
     * @var int
     *          variable to store item id for sorting logs by specific item
     */
    private int $itemId = 0;

    /**
     * Summary of getItems
     * @return array
     *               returns list of items for sorting , eg: products for productLogs and categoried for category logs
     */

    abstract protected function getEntityType(): string;

    /**
     * Summary of getRedirectRoute
     * @return string
     *                return sredirect route string , after completion of sorting functions
     */
    abstract protected function getRedirectRoute(): string;

    /**
     * Summary of getService
     * @return \App\Services\ServicesInterface
     *                                         specific for each logs
     */
    abstract protected function getService(): ServicesInterface;

    public function __construct(
        private DynamoDbService $dynamoDbService,
        private UserService $userService,
    ) {

    }

    public function getItems(): array
    {
        return $this->getService()->getAll();
    }

    public function setAction(string $action): static
    {
        $this->action = $action;
        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAdminId(int $adminId): static
    {
        $this->adminId = $adminId;
        return $this;
    }

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function setTimeInterval(string $timeInterval): static
    {
        $this->timeInterval = $timeInterval;
        return $this;
    }
    public function getTimeInterval(): string
    {
        return $this->timeInterval;
    }

    public function setItemId(int $itemId): static
    {
        $this->itemId = $itemId;
        return $this;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function setTemplateName(string $templateName): static
    {
        $this->templateName = $templateName;
        return $this;
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    public function getAdmins(): array
    {
        $admins = $this->userService->getAllAdmin();
        return $admins;
    }

    //dashboard
    #[Route(path:"/admin/logs", name:"logs_main")]
    public function index(Request $request)
    {

    }


    public function getAllLogs(Request $request, $itemId)
    {

        // Retrieve filters from the request
        $action = $request->getSession()->get('action', $this->getAction());
        $adminId = $request->getSession()->get('adminId', $this->getAdminId());
        $timeInterval = $request->getSession()->get('timeInterval', $this->getTimeInterval());


        // Get product logs by entity type
        $itemLogs = $this->dynamoDbService->getLogsByEntityType($this->getEntityType());

        // Apply filters one by one

        // Filter by Action
        if ($action !== 'All') {
            $itemLogs = array_filter($itemLogs, function ($log) use ($action) {
                return $log->Action === $action;
            });
        }

        // Filter by AdminId
        if ($adminId !== 0) {
            $itemLogs = array_filter($itemLogs, function ($log) use ($adminId) {
                return $log->AdminId == $adminId;
            });
        }

        // Filter by Time Interval (e.g., last 24 hours, last week, last month)
        if ($timeInterval !== 'All') {
            $currentDateTime = new \DateTime('now', new \DateTimeZone('UTC')); // Current time in UTC
            $endDateString = $currentDateTime->format('Y-m-d\TH:i:s\Z'); // ISO 8601 format

            switch ($timeInterval) {
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
            $itemLogs = array_filter($itemLogs, function ($log) use ($startDateString, $endDateString) {
                $logDate = new \DateTime($log->Date);
                return $logDate >= new \DateTime($startDateString) && $logDate <= new \DateTime($endDateString);
            });
        }

        // Filter by ItemId
        if ($itemId !== 0) {
            $itemLogs = array_filter($itemLogs, function ($log) use ($itemId) {
                return $log->EntityId == $itemId;
            });
        }

        // Sort by date, recent at the top
        usort($itemLogs, function ($a, $b) {
            $dateA = new \DateTime($a->Date);
            $dateB = new \DateTime($b->Date);
            return $dateB <=> $dateA; // Sort by descending order (recent first)
        });

        // Return the filtered and sorted product logs
        return $this->render(
            $this->getTemplateName(),
            [
                'itemLogs' => $itemLogs,
                'selectedOptions' => [
                    'action' => $action,
                    'admin' => $adminId,
                    'timeInterval' => $timeInterval,
                    'productId' => $itemId,
                ],
                'admins' => $this->getAdmins(),
                'items' => $this->getItems(),
            ]
        );
    }

    /**
     * Summary of sortLogsByAction
     * @param string $action
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *                                                            filter logs By action , stores filter in session
     *                                                            redirects to logs page
     */
    public function sortLogsByAction(string $action, Request $request)
    {
        $request->getSession()->set('action', $action);

        return $this->redirectToRoute($this->getRedirectRoute());
    }

    /**
     * Summary of sortLogsByAdmin
     * @param int $adminId
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *                                                            filter logs By admin , stores filter in session
     *                                                            redirects to logs page
     */
    public function sortLogsByAdmin(int $adminId, Request $request)
    {
        $request->getSession()->set('adminId', $adminId);
        return $this->redirectToRoute($this->getRedirectRoute());
    }

    /**
     * Summary of sortLogsByTimeInterval
     * @param string $interval
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *                                                            filter logs By time interval , stores filter in session
     *                                                            redirects to logs page
     */
    public function sortLogsByTimeInterval(string $interval, Request $request)
    {
        $request->getSession()->set("timeInterval", $interval);
        return $this->redirectToRoute($this->getRedirectRoute());
    }



}
