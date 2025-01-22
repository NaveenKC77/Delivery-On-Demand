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

    /**
     * Summary of getAllAdmin
     * @return array
     *               used for sorting logs
     *               adds 'All Admin with id 0' to the start of array
     */
    public function getAllAdmin()
    {

        $admins = $this->userRepository->findAllAdmins();

        // adding 0 for All Admin Options in Sort By Admin in Logs
        array_unshift($admins, ['id' => 0,'username' => 'All Admins']);
        return $admins;
    }
}
