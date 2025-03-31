<?php

namespace App\Controller;

use App\Enum\ActiveSidenav;
use App\Services\AppContextInterface;
use App\Services\NotificationServiceInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    public function __construct(private NotificationServiceInterface $notificationService, private AppContextInterface $appContext)
    {
    }
    #[Route('/user/notification', name: 'app_notification')]
    public function index(): Response
    {
        // get the authenticated user
        $user = $this->appContext->getCurrentUser();

        // get all notifications
        $notifications = $this->notificationService->getAllNotifications($user);

        //unread Notifications count
        $unreadNotifications = $this->notificationService->countUnReadNotification($user);

        return $this->render('user/notification/index.html.twig', [
            'user' => $user,
            'notifications' => $notifications,
            'unreadNotificationsCount' => $unreadNotifications,
            'active' => ActiveSidenav::NOTIFICATION,
        ]);
    }

    #[Route('/user/notification/read/{id}', name:'app_notification_read')]

    public function markNotificationRead(int $id)
    {
        try {
            $this->notificationService->markNotificationRead($id);
            return $this->redirectToRoute('app_notification');
        } catch (Exception $e) {
            dd($e->getMessage());
            return $this->redirectToRoute('app_notification');
        }
    }
}
