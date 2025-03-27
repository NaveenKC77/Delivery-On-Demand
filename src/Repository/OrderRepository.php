<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    use EntityPersistanceTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Summary of getAllQueryBuilder
     * @return QueryBuilder
     *                      returns all rows in order table
     */
    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
        ->addSelect('orderDetails')
        ->leftJoin('o.orderDetails', 'orderDetails')
        ->orderBy('o.createdAt', 'DESC');
    }

    /**
     * Summary of findOrdersByUserQueryBuilder
     * @param mixed $customerId
     * @return QueryBuilder
     *                      returns order for specific User
     */
    public function findOrdersByUserQueryBuilder($customerId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
        ->andWhere('o.customer = :val')
        ->setParameter('val', $customerId)
        ->orderBy('o.createdAt', 'DESC');

    }

    //overriding parent method to make it public
    public function getEntityManager(): \Doctrine\ORM\EntityManagerInterface
    {
        return parent::getEntityManager();
    }



    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
