<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractUserController extends AbstractController
{
    use FormControllerTrait;

    abstract protected function getRoles(): array;
}
