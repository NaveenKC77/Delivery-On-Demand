<?php

namespace App\Controller;

use App\Form\EmployeeRegistrationFormType;
use App\Repository\EmployeeRepository;
use App\Services\EmployeeService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends UserAbstractController
{
    public function __construct(private EmployeeService $employeeService,) {}


    public function getService()
    {
        return $this->employeeService;
    }

    public function getRoles(): array
    {
        return ['ROLE_EMPLOYEE'];
    }

    public function getFormType(): string
    {
        return EmployeeRegistrationFormType::class;
    }


    #[Route('/admin/employee/{page<\d+>}', name: 'app_employee')]
    public function index($page = 1): Response
    {
        $qb = $this->getService()->getAllQueryBuilder();
        $pagination = parent::getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/employee/index.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return parent::read();
    }
    #[Route('/admin/employee/verified/{page<\d+>}', name: 'app_verified_employee')]
    public function verifiedEmployees($page = 1): Response
    {
        $qb = $this->getService()->getAllVerifiedQueryBuilder();
        $pagination = parent::getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/employee/verified.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return parent::read();
    }
}