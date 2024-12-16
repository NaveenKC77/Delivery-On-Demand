<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public static function getVerifiedCriteria(): Criteria
    {
        return Criteria::create()->andWhere(Criteria::expr()->eq('user.isVerified', true));
    }

    /**
     * Summary of getAllCustomersQueryBuilder
     * left joins user table.
     */
    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->addSelect('user')
            ->join('c.user', 'user');
    }

    public function getAllVerifiedQueryBuilder(): QueryBuilder
    {
        return $this->getAllQueryBuilder()->addCriteria(self::getVerifiedCriteria());
    }

    /**
     * Summary of findAll.
     */
    public function findAll(): array
    {
        return $this->getAllQueryBuilder()->getQuery()->getResult();
    }

    public function findAllVerified(): array
    {
        return $this->getAllVerifiedQueryBuilder()->getQuery()->getResult();
    }
    //    /**
    //     * @return Customer[] Returns an array of Customer objects
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

    //    public function findOneBySomeField($value): ?Customer
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
