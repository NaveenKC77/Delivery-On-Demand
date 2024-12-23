<?php

namespace App\Services;

use App\Repository\UserRepository;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getUser($id)
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    public function deleteUser($id)
    {
        $user = $this->getUser($id);
        $this->userRepository->getEntityManager()->remove($user);
        $this->userRepository->getEntityManager()->flush();
    }
}
