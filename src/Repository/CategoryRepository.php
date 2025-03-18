<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getAllQueryBuilder()
    {
        return $this->createQueryBuilder('c')
            ->addSelect('products')
            ->leftJoin('c.products', 'products');
    }


    public function findAllWithProducts()
    {
        return $this->createQueryBuilder('c')
            ->addSelect('products')
            ->leftJoin('c.products', 'products')
            ->getQuery()
            ->getResult();
    }


    public function save(Category $category): void
    {
        $em = $this->getEntityManager();
        $em->persist($category);
        $em->flush();
    }

    public function delete(Category $category): void
    {
        $em = $this->getEntityManager();
        $em->remove($category);
        $em->flush();
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
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

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
