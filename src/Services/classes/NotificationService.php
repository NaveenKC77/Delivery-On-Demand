<?php

namespace App\Services;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;

class NotificationService extends AbstractEntityService implements NotificationServiceInterface
{
    public function __construct(private NotificationRepository $notificationRepository)
    {
    }

    protected function getEntityClass(): string{
        return Notification::class;
    }


    private function getNotification(int $id): Notification{
        return $this->notificationRepository->find($id);
        
    }
    // create new notification
    public function newNotification(User $user, string $title, string $content, string $link): void
    {

        $notification = new Notification($user, $title, $content, $link);

        $this->notificationRepository->save($notification);

    }

    // get all notifications for the user
    public function getAllNotifications(User $user): array
    {
        $notifications = $this->notificationRepository->getNotificationsByUserQueryBuilder($user->getId())
        ->getQuery()
        ->getResult();

        return $notifications;
    }

    //get unreadNotification notifications

    public function getAllUnreadNotifications(User $user):array
    {
        $notifications = $this->notificationRepository->getUnReadNotificationsByUserQueryBuilder($user->getId())->getQuery()->getResult();
        return $notifications;
    }

    public function countUnReadNotification(User $user): int
    {

        $count = count($this->notificationRepository->getUnReadNotificationsByUserQueryBuilder($user->getId())->getQuery()->getResult());
        return $count;
    }
    //mark notification read
    public function markNotificationRead(int $notificationId): bool
    {
        $notification = $this->getNotification($notificationId);

        if (!$notification->isRead()) {
            $notification->setRead(true);

            $this->notificationRepository->save($notification);
        }
        return true;
    }
}
