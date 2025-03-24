<?php

namespace App\Controller;

use App\Services\NotificationService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{

    public function __construct(private NotificationService $notificationService){}
    #[Route('/user/notification', name: 'app_notification')]
    public function index(): Response
    {

        // get all user
        $user = $this->getUser();

        // get all notifications
        $notifications = $this->notificationService->getAllNotifications($user);

        //unread Notifications count
        $unreadNotifications = $this->notificationService->countUnReadNotification($user);

        return $this->render('user/notification/index.html.twig', [
            'user'=>$user,
            'notifications' => $notifications,
            'unread_notifications' => $unreadNotifications
        ]);
    }

    #[Route('/user/notification/read/{id}', name:'app_notification_read')]

    public function markNotificationRead(int $id){
        try{
            $this->notificationService->markNotificationRead($id);
            return $this->redirectToRoute('app_notification');
        }
        catch(Exception $e){
            dd($e->getMessage());
            return $this->redirectToRoute('app_notification');
        }
    }
}
