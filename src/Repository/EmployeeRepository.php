<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
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
        return $this->createQueryBuilder('e')
            ->addSelect('user')
            ->leftJoin('e.user', 'user');
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
    //     * @return Employee[] Returns an array of Employee objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Employee
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
