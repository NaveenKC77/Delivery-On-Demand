<?php

namespace App\Controller;

use App\Form\CustomerRegistrationFormType;
use App\Services\CustomerService;
use App\Services\CustomerServiceInterface;
use App\Services\PaginatorService;
use App\Services\PaginatorServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerController extends AbstractReadController
{
    public function __construct(
        private CustomerServiceInterface $customerService,
        private PaginatorServiceInterface $paginatorService
    ) {
        parent::__construct($paginatorService);
    }

    public function getRoles(): array
    {
        return ['ROLE_CUSTOMER'];
    }

    public function getFormType(): string
    {
        return CustomerRegistrationFormType::class;
    }

    #[Route('/admin/customer/{page<\d+>}', name: 'admin_customer')]
    public function index(int $page = 1): Response
    {
        $qb = $this->customerService->getAllQueryBuilder();
        $pagination = $this->getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/customer/index.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return $this->read();
    }

    #[Route('/admin/customer/verified/{page<\d+>}', name: 'admin_verified_customer')]
    public function verifiedCustomers(int $page = 1): Response
    {
        $qb = $this->customerService->getAllVerifiedQueryBuilder();
        $pagination = $this->getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/customer/verified.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return $this->read();
    }
}
