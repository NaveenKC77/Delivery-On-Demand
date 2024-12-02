<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Services\CategoryService;
use App\Services\ServicesInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractFormController
{
    public function __construct(private CategoryService $categoryService, private CategoryRepository $categoryRepository)
    {
        parent::__construct();
    }

    //get form type
    public function getFormType(): string
    {

        return CategoryFormType::class;
    }

    //returns service
    public function getService(): ServicesInterface
    {
        return $this->categoryService;
    }

    //returns upload dir 
    public function getUploadDir(): string
    {
        return
            $this->getParameter('kernel.project_dir') . '/assets/images/uploads';
    }

    // display page
    #[Route('/admin/category/{page<\d+>}', name: 'app_admin_category')]
    public function index($page = 1): Response
    {
        $this->setTemplateName('admin/category/index.html.twig');

        // $categories = $this->getService()->getAll();

        $qb = $this->categoryRepository->getAllQueryBuilder();

        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage(10);
        $pagination->setCurrentPage($page);


        $this->setTemplateData(['pager' => $pagination]);
        return parent::read();
    }
    // display page for single item
    #[Route(path: '/admin/category/single/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function singleCategroyPage(int $id)
    {
        $category = $this->getService()->getOneById($id);

        $this->setTemplateName('admin/category/singleCategory.html.twig');
        $this->setTemplateData(['category' => $category]);
        return parent::read();
    }

    #[Route('/admin/category/create', name: 'app_admin_category_create', methods: ['GET', 'POST'])]
    public function createCategory(Request $request)
    {
        $this->setTemplateName('admin/category/create.html.twig');
        $this->setRedirectRoute('admin_category');

        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->getService()->add($category);
            $this->addFlash('success', 'Category ' . $category->getName() . ' successfully added');
            return $this->redirectToRoute($this->getRedirectRoute());
        }

        $this->setTemplateData(['category' => $category, 'form' => $form->createView()]);

        return parent::read();
    }

    // Page to edit a single category
    #[Route('/admin/category/edit/{id}', requirements: ['id' => '\d+'])]
    public function editCategory($id, Request $request): Response
    {
        $this->setTemplateName('admin/category/edit.html.twig');
        $this->setRedirectRoute('admin_category');

        $category = $this->categoryService->getOneById($id);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->edit($category);
            $this->addFlash('success', 'Category : ' . $category->getName() . ' successfully edited.');

            return $this->redirectToRoute($this->getRedirectRoute());
        }
        $this->setTemplateData(['category' => $category, 'form' => $form->createView()]);


        return parent::read();
    }

    // Delete single Category
    #[Route('/admin/category/delete/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteCategory($id)
    {
        $this->setRedirectRoute('admin_category');

        $this->addFlash('error', 'Category successfully deleted.');
        return parent::delete($id);
    }
}
