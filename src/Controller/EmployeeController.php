<?php

namespace App\Controller;


use App\Form\EmployeeFormType;
use App\Repository\EmployeeRepository;
use App\Services\EmployeeService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeController extends AbstractFormController
{
    public function __construct(private EmployeeService $employeeService, private EmployeeRepository $employeeRepository) {}
    public function getFormType(): string
    {
        return EmployeeFormType::class;
    }
    public function getService()
    {
        return $this->employeeService;
    }
    public function getUploadDir(): string
    {
        return '';
    }
    #[Route('/admin/employee/{page<\d+>}', name: 'app_employee')]
    public function index($page = 1): Response
    {
        $qb = $this->employeeRepository->getAllQueryBuilder();
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage(10);
        $pagination->setCurrentPage($page);
        $customers = $this->getService()->getAll();

        return $this->render('admin/employee/index.html.twig', [
            'pager' => $pagination,
        ]);
    }
    #[Route('/admin/employee/verified/{page<\d+>}', name: 'app_verified_employee')]
    public function verifiedEmployees($page = 1): Response
    {
        $qb = $this->employeeRepository->getAllVerifiedQueryBuilder();
        $verified = $qb->getQuery()->getResult();
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage(10);
        $pagination->setCurrentPage($page);



        return $this->render('admin/employee/verified.html.twig', [
            'pager' => $pagination,
        ]);
    }
}
