<?php

namespace App\Controller;

use App\Form\CustomerRegistrationFormType;
use App\Services\CustomerService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomerController extends AbstractController
{
    use FormControllerTrait;
    public function __construct(
        private CustomerService $customerService,
    ) {
    }

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
        return CustomerRegistrationFormType::class;
    }

    #[Route('/admin/customer/{page<\d+>}', name: 'app_customer')]
    public function index(int $page = 1): Response
    {
        $qb = $this->getService()->getAllQueryBuilder();
        $pagination = $this->getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/customer/index.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return $this->read();
    }

    #[Route('/admin/customer/verified/{page<\d+>}', name: 'app_verified_customer')]
    public function verifiedCustomers(int $page = 1): Response
    {
        $qb = $this->getService()->getAllVerifiedQueryBuilder();
        $pagination = $this->getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/customer/verified.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return $this->read();
    }
}
