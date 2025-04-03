<?php

namespace App\Services;

use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;

class UserService extends AbstractEntityService implements UserServiceInterface
{
    public function __construct(private UserRepository $repository, private Security $security)
    {
        parent::__construct($repository);
    }

    protected function getEntityClass(): string
    {
        return User::class;
    }

    public function getLoggedInUser(): User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new Exception('User Not Logged In');
        }
        return $user;
    }
    public function getAllVerifiedQueryBuilder(): QueryBuilder
    {
        return $this->repository->getAllVerifiedQueryBuilder();
    }


    /**
     * Get User by id
     * Ensures type safety
     * @param mixed $id
     * @return \App\Entity\User
     */
    private function getUser($id): User|null
    {
        return $this->getOneById($id);
    }

    public function deleteUser($id): void
    {
        $user = $this->getUser($id);
        $this->repository->delete($user);
    }


    public function getAllAdmin(): array
    {
        $admins = $this->repository->findAllAdmins();

        // adding 0 for All Admin Options in Sort By Admin in Logs
        array_unshift($admins, ['id' => 0,'username' => 'All Admins']);
        return $admins;
    }



}
