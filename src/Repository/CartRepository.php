<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository implements CartRepositoryInterface
{
    use EntityPersistanceTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder("c");
    }
    public function findByCustomerId(int $customerId): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.customer = :val')
            ->setParameter('val', $customerId)
            ->addSelect('items', 'product')
            ->leftjoin('c.cartItems', 'items')
            ->leftJoin('items.product', 'product')
            ->getQuery()
            ->getOneOrNullResult();
    }

    //overriding parent method to make it public
    public function getEntityManager(): \Doctrine\ORM\EntityManagerInterface
    {
        return parent::getEntityManager();
    }


    //    /**
    //     * @return Cart[] Returns an array of Cart objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cart
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
