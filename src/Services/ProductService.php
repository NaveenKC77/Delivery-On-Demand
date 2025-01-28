<?php

namespace App\Services;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductService implements NonUserTypeServicesInterface
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /**
     * Summary of getAllQueryBuilder
     * @return \Doctrine\ORM\QueryBuilder
     *                                    queryBuilder to get All Products with categories joined
     */
    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->productRepository->getAllQueryBuilder();
    }

    /**
     * Summary of getAll
     * @return array of all products joined with categories
     */
    public function getAll(): array
    {
        return $this->productRepository->findAllWithCategories();
    }

    public function getOneById(int $id): Product | null
    {
        return $this->productRepository->findOneById($id);
    }

    public function getFeaturedProducts(){
        return $this->productRepository->findFeaturedProducts(); 
    }


    public function add($entity): void
    {
        $this->productRepository->getEntityManager()->persist($entity);
        $this->productRepository->getEntityManager()->flush();
    }

    public function delete($entity)
    {
        $this->productRepository->getEntityManager()->remove($entity);
        $this->productRepository->getEntityManager()->flush();
    }


    public function edit($entity)
    {
        $this->productRepository->getEntityManager()->persist($entity);
        $this->productRepository->getEntityManager()->flush();
    }


    // set a unique name for uploaded file, uploads it, and returns new FileName
    public function processUpload($imagePath, $uploadDir): string
    {
        $newFileName = uniqid() . '.' . $imagePath->guessExtension();

        try {
            $imagePath->move(
                $uploadDir,
                $newFileName
            );
        } catch (FileException $e) {
            return $e->getMessage();
        }

        return $newFileName;
    }
}
