<?php

namespace App\Services;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(private EntityManagerInterface $em, private UserRepository $userRepository)
    {
    }

    public function getUser($id)
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }

    public function deleteUser($id)
    {
        $user = $this->getUser($id);
        $this->em->remove($user);
        $this->em->flush();
    }
}
