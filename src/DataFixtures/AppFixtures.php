<?php

namespace App\DataFixtures;


use App\Factory\CategoryFactory;
use App\Factory\EmployeeFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        UserFactory::new()->createOne(
            ['email' => 'admin@dod.com',
                'username' => 'admin',
                'roles' => ['ROLE_ADMIN'],
                'isVerified' => true,
                'firstName' => 'Naveen',
                'lastName' => 'K C',
                'phoneNumber'=> '+61433941726',
            ]
                
        );

       
        CategoryFactory::new()->createMany(4);
        ProductFactory::new()->createMany(16);
    }
}
