<?php

namespace App\Services;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use App\Repository\NotificationRepositoryInterface;

class NotificationService extends AbstractEntityService implements NotificationServiceInterface
{
    public function __construct(private NotificationRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function getEntityClass(): string
    {
        return Notification::class;
    }


    /**
     * Get Notification By Id
     * Ensures type safety
     * @param int $id
     * @return \App\Entity\Notification
     */
    private function getNotification(int $id): Notification
    {
        return $this->getOneById($id);

    }
    // create new notification
    public function newNotification(User $user, string $title, string $content, string $link): void
    {

        $notification = new Notification($user, $title, $content, $link);

        $this->repository->save($notification);

    }

    // get all notifications for the user
    public function getAllNotifications(User $user): array
    {
        $notifications = $this->repository->getNotificationsByUserQueryBuilder($user->getId())
        ->getQuery()
        ->getResult();

        return $notifications;
    }

    //get unreadNotification notifications

    public function getAllUnreadNotifications(User $user): array
    {
        $notifications = $this->repository->getUnReadNotificationsByUserQueryBuilder($user->getId())->getQuery()->getResult();
        return $notifications;
    }

    public function countUnReadNotification(User $user): int
    {

        $count = count($this->repository->getUnReadNotificationsByUserQueryBuilder($user->getId())->getQuery()->getResult());
        return $count;
    }
    //mark notification read
    public function markNotificationRead(int $notificationId): void
    {
        $notification = $this->getNotification($notificationId);

        if (!$notification->isRead()) {
            $notification->setRead(true);
            $this->repository->save($notification);

        }

    }
}
