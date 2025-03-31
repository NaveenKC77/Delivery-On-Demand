<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface NotificationRepositoryInterface extends EntityRepositoryInterface
{
    /**
     * Get all notifications as a QueryBuilder.
     *
     * @return QueryBuilder
     */
    public function getAllQueryBuilder(): QueryBuilder;

    /**
     * Get notifications for a specific user.
     *
     * @param int $userId
     * @return QueryBuilder
     */
    public function getNotificationsByUserQueryBuilder(int $userId): QueryBuilder;

    /**
     * Get unread notifications for a specific user.
     *
     * @param int $userId
     * @return QueryBuilder
     */
    public function getUnReadNotificationsByUserQueryBuilder(int $userId): QueryBuilder;
}
