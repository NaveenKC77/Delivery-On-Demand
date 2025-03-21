<?php


namespace App\Services;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;

class NotificationService{

    public function __construct(private NotificationRepository $notificationRepository){}


    // create new notification
    public function newNotification(User $user,string $title,string $content){

        $notification = new Notification($user, $title, $content);

        $this->notificationRepository->save($notification);

    }

    // get all notifications for the user
    public function getAlNotifications (User $user) : array{
        $notifications = $this->notificationRepository->getNotificationsByUserQueryBuilder($user->getId())
        ->getQuery()
        ->getResult();

        return $notifications;
    }

    //get unreadNotification Count

    public function countUnReadNotification(User $user){

        $count =count($this->notificationRepository->getUnReadNotificationsByUserQueryBuilder($user->getId())->getQuery()->getResult());
        return $count;
    }
    //mark notification read
    public function markNotificationRead(int $notificationId){
        $notification = $this->notificationRepository->findOneById($notificationId);

        if(!$notification->isRead()){
            $notification->setRead(true);

            $this->notificationRepository->save($notification);
        }
        return true;
    }
}