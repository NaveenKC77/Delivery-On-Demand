<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Notification;

interface NotificationServiceInterface extends EntityServicesInterface
{
    /**
     * Creates a new notification for the user
     *
     * @param User $user The recipient user
     * @param string $title Notification title
     * @param string $content Notification content
     * @param string $link Optional link associated with the notification
     */
    public function newNotification(User $user, string $title, string $content, string $link): void;

    /**
     * Gets all notifications for a user (both read and unread)
     *
     * @param User $user The user to get notifications for
     * @return Notification[] Array of Notification entities
     */
    public function getAllNotifications(User $user): array;

    /**
     * Gets all unread notifications for a user
     *
     * @param User $user The user to get notifications for
     * @return Notification[] Array of unread Notification entities
     */
    public function getAllUnreadNotifications(User $user): array;

    /**
     * Counts all unread notifications for a user
     *
     * @param User $user The user to count notifications for
     * @return int Number of unread notifications
     */
    public function countUnReadNotification(User $user): int;

    /**
     * Marks a notification as read
     *
     * @param int $notificationId ID of the notification to mark as read
     * @return bool True if the notification was updated, false if it was already read
     */
    public function markNotificationRead(int $notificationId): void;


}
