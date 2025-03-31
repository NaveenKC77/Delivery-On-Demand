<?php

namespace App\Controller;

use App\Form\EmployeeRegistrationFormType;
use App\Services\EmployeeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractReadController
{
    public function __construct(private EmployeeServiceInterface $employeeService)
    {
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
    public function index(int $page = 1): Response
    {
        $qb = $this->employeeService->getAllQueryBuilder();
        $pagination = $this->getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/employee/index.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return $this->read();
    }

    #[Route('/admin/employee/verified/{page<\d+>}', name: 'app_verified_employee')]
    public function verifiedEmployees(int $page = 1): Response
    {
        $qb = $this->employeeService->getAllVerifiedQueryBuilder();
        $pagination = $this->getPagination($qb, $page, 10);

        $this->setTemplateName('/admin/employee/verified.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return $this->read();
    }
}
