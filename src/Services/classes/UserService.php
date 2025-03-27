<?php

namespace App\Services;

use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function getOneById(int $id): User | null
    {
        return $this->userRepository->findOneById($id);
    }

    public function getAllQueryBuilder(): QueryBuilder
    {
        return $this->userRepository->getAllQueryBuilder();
    }

    public function getAllVerifiedQueryBuilder(): QueryBuilder
    {
        return $this->userRepository->getAllVerifiedQueryBuilder();
    }

    public function getUser($id): User|null
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    public function deleteUser($id)
    {
        $user = $this->getUser($id);
        $this->userRepository->delete($user);
    }

    /**
     *  used for sorting logs
     *  adds 'All Admin with id 0' to the start of array
     */
    public function getAllAdmin()
    {
        $admins = $this->userRepository->findAllAdmins();

        // adding 0 for All Admin Options in Sort By Admin in Logs
        array_unshift($admins, ['id' => 0,'username' => 'All Admins']);
        return $admins;
    }



}
