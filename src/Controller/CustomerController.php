<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Services\CustomerService;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class CustomerController extends UserAbstractController
{
    public function __construct(private CustomerService $customerService) {}

    public function getService()
    {
        return $this->customerService;
    }

    public function getRoles(): array
    {
        return ['ROLE_CUSTOMER'];
    }

    public function getFormType(): string
    {
        return RegistrationFormType::class;
    }


    #[Route('/admin/customer/{page<\d+>}', name: 'app_customer')]
    public function index($page = 1): Response
    {
        $qb = $this->getService()->getAllQueryBuilder();
        $pagination = parent::getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/customer/index.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return parent::read();
    }
    #[Route('/admin/customer/verified/{page<\d+>}', name: 'app_verified_customer')]
    public function verifiedCustomers($page = 1): Response
    {
        $qb = $this->getService()->getAllVerifiedQueryBuilder();
        $pagination = parent::getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/customer/verified.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return parent::read();
    }
}
