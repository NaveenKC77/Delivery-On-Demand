<?php

namespace App\Controller;


use App\Services\LogFilterService;
use App\Services\LoggerService;
use App\Services\ServicesInterface;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;

class UserLogsController extends AbstractLogsController
{
    public function __construct(private UserService $userService, private LoggerService $loggerService, private LogFilterService $logFilterService,private CacheInterface $logsCache)
    {

        parent::__construct($this->loggerService, $this->userService, $this->logFilterService,$this->logsCache);
    }

    public function getEntityType(): string
    {
        return 'User';
    }

    public function getRedirectRoute(): string
    {
        return 'user_logs';
    }

    public function getService(): ServicesInterface
    {
        return $this->userService;
    }

    /**
     * main page for all User logs
     */
    #[Route(path:"/admin/logs/user", name:"user_logs")]
    public function getAllUserLogs(Request $request): Response
    {
        $this->setTemplateName('admin/logs/user.html.twig');
        // get filter for specific User id
        $itemId = $request->getSession()->get('userLogId', $this->getItemId());
        return parent::getAllLogs($request, $itemId);
    }

    #[Route(path:"/admin/logs/user/action/{action}", name:"user_logs_action")]
    public function sortUserLogsByAction(string $action, Request $request)
    {
        $request->getSession()->set('action', $action);

        return $this->redirectToRoute($this->getRedirectRoute());
    }


    #[Route(path:"/admin/logs/user/timeInterval/{interval}", name:"user_logs_time")]
    public function sortUserLogsByTimeInterval(string $interval, Request $request)
    {
        return parent::sortLogsByTimeInterval($interval, $request);
    }

    #[Route(path:"/admin/logs/user/user/{id}", name:"user_logs_single")]
    public function sortUserLogsBySpecificItem(int $id, Request $request)
    {
        // set filter for specific UserId
        $request->getSession()->set("userLogId", $id);

        return $this->redirectToRoute($this->getRedirectRoute());

    }
}
