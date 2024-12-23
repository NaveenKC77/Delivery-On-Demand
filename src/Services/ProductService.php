<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductService implements ServicesInterface
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getAll(): array
    {
        return $this->productRepository->findAllWithCategories();
    }

    public function add($entity): void
    {
        $this->productRepository->getEntityManager()->persist($entity);
        $this->productRepository->getEntityManager()->flush();
    }

    public function delete($id)
    {
        $object = $this->productRepository->find($id);
        $this->productRepository->getEntityManager()->remove($object);
        $this->productRepository->getEntityManager()->flush();
    }

    public function edit($entity)
    {
        $this->productRepository->getEntityManager()->persist($entity);
        $this->productRepository->getEntityManager()->flush();
    }

    public function getOneById(int $id)
    {
        return $this->productRepository->findOneById($id);
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
