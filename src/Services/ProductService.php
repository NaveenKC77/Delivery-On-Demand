<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductService implements ServicesInterface
{
    public function __construct(private ProductRepository $productRepository, private EntityManagerInterface $em)
    {
    }

    public function getAll(): array
    {
        return $this->productRepository->findAllWithCategories();
    }

    public function add($entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function delete($id)
    {
        $object = $this->productRepository->find($id);
        $this->em->remove($object);
        $this->em->flush();
    }

    public function edit($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
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
