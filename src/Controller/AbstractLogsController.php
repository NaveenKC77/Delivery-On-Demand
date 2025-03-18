<?php

namespace App\Controller;

use App\Services\LogFilterService;
use App\Services\LoggerService;
use App\Services\ServicesInterface;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

abstract class AbstractLogsController extends AbstractController
{
    /**
     *  list of admins in the system
     */
    private array $admins = [];

    /**
     *all the items to be used for specific log sort
     */
    private array $items = [];

    /*
     *twig template
     */
    private string $templateName;

    /**
     *sort logs : Create, Update , Delete
     */
    private string $action = 'All';

    /**
     *Selected admin  for sorting logs
     */
    private int $adminId = 0;

    /**
     * to sort logs for last 24hrs, 7 days etc.
     */
    private string $timeInterval = 'All';

    /**
     *variable to store item id for sorting logs by specific item
     */
    private int $itemId = 0;

    /**
     *returns list of items for sorting , eg: products for productLogs and categoried for category logs
     */

    abstract protected function getEntityType(): string;

    /**
     *return sredirect route string , after completion of sorting functions
     */
    abstract protected function getRedirectRoute(): string;

    /**
     * specific for each logs
     */
    abstract protected function getService(): ServicesInterface;

    public function __construct(
        private LoggerService $loggerService,
        private UserService $userService,
        private LogFilterService $logFilterService,
        private CacheInterface $logsCache
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


    public function getAllLogs(Request $request, $itemId)
    {
        // Retrieve filters from the request
        $action = $request->getSession()->get('action', $this->getAction());
        $adminId = $request->getSession()->get('adminId', $this->getAdminId());
        $timeInterval = $request->getSession()->get('timeInterval', $this->getTimeInterval());

        //pass into cache function
        $entityType = $this->getEntityType();

        // Get logs by entity type
        // get item logs from cache , if empty , from dynamo Db
        $itemLogs = $this->logsCache->get('logs',function(ItemInterface $item)use($entityType){
           $item->expiresAfter(600);
            return $this->loggerService->getLogsByEntityType($entityType);
        });

        // $itemLogs = $this->loggerService->getLogsByEntityType($this->getEntityType());
    

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
            $itemLogs = $this->logFilterService->filterLogsByTimeInterval($timeInterval, $itemLogs);
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
                    'itemId' => $itemId,
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
