<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository implements NotificationRepositoryInterface
{
    use EntityPersistanceTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * Summary of getAllQueryBuilder
     * @return QueryBuilder
     * all rows from notifications table
     */
    public function getAllQueryBuilder(): QueryBuilder{
        return $qb = $this->createQueryBuilder("n");
    }
    /**
     * Summary of getNotificationsByUserQueryBuilder
     * @param int $userId
     * @return QueryBuilder
     * return notifications for each user
     */
    public function getNotificationsByUserQueryBuilder(int $userId): QueryBuilder{
        return $this->createQueryBuilder("n")
        ->andWhere('n.user = :val')
        ->setParameter('val',$userId)
        ->orderBy('n.createdAt');
    }

    /**
     * Summary of getUnReadNotificationsByUserQueryBuilder
     * @param int $userId
     * @return QueryBuilder
     * returns unread notifications for each user 
     */
    public function getUnReadNotificationsByUserQueryBuilder(int $userId): QueryBuilder{
        return $this->createQueryBuilder("n")
        ->andWhere('n.user = :userId')
        ->setParameter('userId', $userId)
        ->andWhere('n.isRead = :isRead')
        ->setParameter('isRead', 0)
        ->orderBy('n.createdAt');
    }

//    /**
//     * @return Notification[] Returns an array of Notification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Notification
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
