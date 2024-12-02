<?php

namespace App\Controller;

use App\Form\CustomerFormType;
use App\Repository\CustomerRepository;
use App\Services\CustomerService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomerController extends AbstractFormController
{
    public function __construct(private CustomerService $customerService, private CustomerRepository $customerRepository) {}
    public function getFormType(): string
    {
        return CustomerFormType::class;
    }
    public function getService()
    {
        return $this->customerService;
    }
    public function getUploadDir(): string
    {
        return '';
    }
    #[Route('/admin/customer/{page<\d+>}', name: 'app_customer')]
    public function index($page = 1): Response
    {
        $qb = $this->customerRepository->getAllQueryBuilder();
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage(10);
        $pagination->setCurrentPage($page);


        return $this->render('admin/customer/index.html.twig', [
            'pager' => $pagination,
        ]);
    }
    #[Route('/admin/customer/verified/{page<\d+>}', name: 'app_verified_customer')]
    public function verifiedCustomers($page = 1): Response
    {
        $qb = $this->customerRepository->getAllVerifiedQueryBuilder();
        $verified = $qb->getQuery()->getResult();
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage(10);
        $pagination->setCurrentPage($page);



        return $this->render('admin/customer/verified.html.twig', [
            'pager' => $pagination,
        ]);
    }
    #[Route('/admin/customer/edit/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editCustomer($id, Request $request)
    {
        $this->setTemplateName('admin/customer/edit.html.twig');
        $this->setRedirectRoute('admin_customer');

        $customer = $this->getService()->getOneById($id);


        $this->form = $this->createForm(CustomerFormType::class, $customer);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {


            $this->getService()->edit($customer);
            $this->addFlash('success', 'Customer successfully edited.');

            return $this->redirectToRoute($this->getRedirectRoute());
        }


        $this->setTemplateData(['form' => $this->form->createView(), 'customer' => $customer]);

        return parent::read();
    }
}
