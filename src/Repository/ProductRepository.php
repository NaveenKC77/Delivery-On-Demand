<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $product): void
    {
        $em = $this->getEntityManager();
        $em->persist($product);
        $em->flush();
    }

    public function delete(Product $product): void
    {
        $em = $this->getEntityManager();
        $em->remove($product);
        $em->flush();
    }

    /**
     * Returns query builder for pagerfanta pagination.
     *
     * @return QueryBuilder
     */
    public function getAllQueryBuilder()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('category')
            ->innerJoin('p.category', 'category')
            ->orderBy('p.id', 'ASC');
    }

    public function getByCategoriesQuery($categoryId)
    {
        return $this->createQueryBuilder('p')
        ->where('p.category = :categoryId')
        ->setParameter('categoryId',$categoryId);
    }

    /**
     * Summary of findAllWithCategories
     * inner joins categories to make less db requests.
     *
     * @return array
     */
    public function findAllWithCategories()
    {
        return $this->getAllQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function findFeaturedProducts()
    {
        return $this->getAllQueryBuilder()
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
