<?php

namespace App\Services;


use App\Repository\EmployeeRepository;
use App\Services\ServicesInterface;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeService implements ServicesInterface
{
    public function __construct(private EmployeeRepository $employeeRepository, private EntityManagerInterface $em) {}

    /**
     * @inheritDoc
     */
    public function add($entity): void {}

    /**
     * @inheritDoc
     */
    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function edit($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->employeeRepository->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getOneById(int $id)
    {
        $this->employeeRepository->findOneById($id);
    }

    /**
     * @inheritDoc
     */
    public function processUpload($imagePath, $uploadDir): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function returnCardProperties(): array
    {
        return [];
    }
}
