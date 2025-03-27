<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    use EntityPersistanceTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Summary of getAllQueryBuilder
     * @return QueryBuilder
     *                      returns all products joined with its categories
     */
    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->addSelect('category')
            ->innerJoin('p.category', 'category')
            ->orderBy('p.id', 'ASC');
    }


    /**
     * Summary of getByCategoriesQueryBuilder
     * @param mixed $categoryId
     * @return QueryBuilder
     *                      return all products by categories
     */
    public function getByCategoriesQueryBuilder($categoryId)
    {
        return $this->createQueryBuilder('p')
        ->where('p.category = :categoryId')
        ->setParameter('categoryId', $categoryId);
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

    /**
     * Summary of findFeaturedProducts
     * return featured products for homepage
     * logic to be changed
     */
    public function findFeaturedProducts()
    {
        return $this->getAllQueryBuilder()
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }
    //overriding parent method to make it public
    public function getEntityManager(): \Doctrine\ORM\EntityManagerInterface
    {
        return parent::getEntityManager();
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
