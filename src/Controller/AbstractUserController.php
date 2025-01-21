<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Summary of AbstractUserController
 * for UserType Entities : User, Customer and Employee
 * extends formControllerTrait for read, pagination and properties
 * has getRoles() abstract method
 */
abstract class AbstractUserController extends AbstractController
{
    use FormControllerTrait;

    abstract protected function getRoles(): array;
}
