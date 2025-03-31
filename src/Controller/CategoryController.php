<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Services\CategoryEventDispatcherService;
use App\Services\CategoryServiceInterface;
use App\Services\EntityServicesInterface;
use App\Services\PaginatorServiceInterface;
use App\Services\ProductServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractCRUDController
{
    public function __construct(
        private CategoryServiceInterface $categoryService,
        private CategoryEventDispatcherService  $eventDispatcherService,
        private ProductServiceInterface $productService,
        private PaginatorServiceInterface $paginatorService
    ) {
        parent::__construct($paginatorService);
    }


    public function getEntityName(): string
    {
        return "Category";
    }

    public function getEntityServiceType(): EntityServicesInterface
    {
        return $this->categoryService;
    }

    public function getRedirectionRoute(): string
    {
        return "app_category";
    }
    // get form type
    public function getFormType(): string
    {
        return CategoryFormType::class;
    }

    // returns service
    public function getService(): EntityServicesInterface
    {
        return $this->categoryService;
    }

    // returns upload dir
    public function getUploadDir(): string
    {
        return $this->getParameter('kernel.project_dir') . '/assets/images/uploads';
    }

    /**
     * Hook for post-create actions.
     */
    protected function postCreateHook(object $entity): void
    {
        $this->eventDispatcherService->dispatchCategoryCreatedEvent($entity);
    }

    /**
     * Hook for post-update actions.
     */
    protected function postUpdateHook(object $entity): void
    {
        $this->eventDispatcherService->dispatchCategoryUpdatedEvent($entity);
    }

    /**
     * Hook for post-delete actions.
     */
    protected function postDeleteHook(object $entity): void
    {
        $this->eventDispatcherService->dispatchCategoryDeletedEvent($entity);
    }

    // display page
    #[Route('/admin/category/{page<\d+>}', name: 'admin_category')]
    public function index(int $page = 1): Response
    {
        // fetches queryBuyilder that returns all categories
        $qb = $this->getService()->getAllQueryBuilder();

        // pagination setup
        $pagination = parent::getPagination($qb, $page, 10);

        $this->setTemplateName('admin/category/index.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return parent::read();
    }

    // display page for single item
    #[Route(path: '/admin/category/show/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'], name:'admin_category_view')]
    public function singleCategoryPage(int $id)
    {
        $category = $this->categoryService->getCategory($id);

        $this->setTemplateName('admin/category/show.html.twig');
        $this->setTemplateData(['category' => $category]);

        return parent::read();
    }

    // add new category
    #[Route('/admin/category/create', name: 'admin_category_create', methods: ['GET', 'POST'])]
    public function createCategory(Request $request)
    {
        $this->setTemplateName('admin/category/create.html.twig');
        $this->setRedirectRoute('app_admin_category');
        $this->setData(new Category());

        $result = parent::create($request);

        $this->setTemplateData(['form' => $result]);

        return parent::read();
    }

    // Page to edit a single category
    #[Route('/admin/category/edit/{id}', name:"admin_category_edit", requirements: ['id' => '\d+'])]
    public function editCategory(int $id, Request $request): Response
    {
        $this->setTemplateName('admin/category/edit.html.twig');
        $this->setRedirectRoute('app_admin_category');

        $category = $this->categoryService->getCategory($id) ;
        $result = parent::update($id, $request);

        $this->setTemplateData(['category' => $category, 'form' => $result]);

        return parent::read();
    }

    // Delete single Category
    #[Route('/admin/category/delete/{id}', name:"admin_category_delete", requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteCategory(int $id)
    {
        $this->setRedirectRoute('app_admin_category');
        $category = $this->categoryService->getOneById($id);
        return parent::delete($id);

    }

    // Customer Interface Category Page

    #[Route('/category/{page<\d+>}', name:'app_category')]

    public function userCategory(int $page = 1)
    {

        // fetches queryBuyilder that returns all categories
        $qb = $this->getService()->getAllQueryBuilder();

        // pagination setup
        $pagination = parent::getPagination($qb, $page, 10);
        $this->setTemplateData(['pager' => $pagination]);

        $this->setTemplateName('category/index.html.twig');
        $this->setRedirectRoute('app_main');
        return parent::read();
    }

    // User UI Page for single category

    #[Route('category/view/{id}/{page<\d+>}', name:'app_category_view', requirements:['id' => '\d+'])]
    public function viewCategory(int $id, int $page = 1)
    {

        // query builder to get products for the specific category
        $qb = $this->productService->getProductsByCategoriesQb($id);
        $pagination = parent::getPagination($qb, $page, 5);

        // getting dtaa for category
        $category =  $this->categoryService->getCategory($id);
        $this->setTemplateData(['pager' => $pagination,'category' => $category]);

        $this->setTemplateName('category/show.html.twig');

        return parent::read();
    }
}
