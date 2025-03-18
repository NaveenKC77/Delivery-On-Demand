<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Services\CategoryEventDispatcherService;
use App\Services\CategoryService;
use App\Services\ServicesInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractFormController
{
    public function __construct(
        private CategoryService $categoryService,
        private CategoryRepository $categoryRepository,
        private CategoryEventDispatcherService  $eventDispatcherService,
        private ProductRepository $productRepository, 
    ) {
    }

    // get form type
    public function getFormType(): string
    {
        return CategoryFormType::class;
    }

    // returns service
    public function getService(): ServicesInterface
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
    #[Route('/admin/category/{page<\d+>}', name: 'app_admin_category')]
    public function index(int $page = 1): Response
    {
        // fetches queryBuyilder that returns all categories
        $qb = $this->categoryRepository->getAllQueryBuilder();

        // pagination setup
        $pagination = parent::getPagination($qb, $page, 10);

        $this->setTemplateName('admin/category/index.html.twig');
        $this->setTemplateData(['pager' => $pagination]);

        return parent::read();
    }

    // display page for single item
    #[Route(path: '/admin/category/single/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function singleCategoryPage(int $id)
    {
        $category = $this->getService()->getOneById($id);

        $this->setTemplateName('admin/category/singleCategory.html.twig');
        $this->setTemplateData(['category' => $category]);

        return parent::read();
    }

    // add new category
    #[Route('/admin/category/create', name: 'app_admin_category_create', methods: ['GET', 'POST'])]
    public function createCategory(Request $request)
    {
        $this->setTemplateName('admin/category/create.html.twig');
        $this->setRedirectRoute('app_admin_category');
        $this->setMessage('New Category Successfully Added');

        $this->setData(new Category());

        $result = parent::create($request);
        if (!$result instanceof FormInterface) {

            try {
                // triggering event to store log in dynamodb
                $this->postCreateHook($this->getData());

                return $result;
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute($this->getRedirectRoute());
            }

        }
        $this->form = $result;

        $this->setTemplateData(['form' => $this->form]);

        return parent::read();
    }

    // Page to edit a single category
    #[Route('/admin/category/edit/{id}', requirements: ['id' => '\d+'])]
    public function editCategory(int $id, Request $request): Response
    {
        $this->setTemplateName('admin/category/edit.html.twig');
        $this->setRedirectRoute('app_admin_category');

        $category = $this->categoryService->getOneById($id);
        $this->setMessage($category->getId() . ' successfully edited.');

        $result = parent::update($category, $request);
        if (!$result instanceof FormInterface) {
            try {

                // triggering event to store log in dynamodb
                $this->postUpdateHook($this->getData());

                return $result;
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute($this->getRedirectRoute());
            }
        }
        $this->form = $result;
        $this->setTemplateData(['category' => $category, 'form' => $this->form]);

        return parent::read();
    }

    // Delete single Category
    #[Route('/admin/category/delete/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteCategory(int $id)
    {
        $this->setRedirectRoute('app_admin_category');

        $this->setMessage('Category with id: ' . $id . ' deleted successfully');

        try {
            $category = $this->categoryService->getOneById($id);
            // triggering event to store log in dynamodb
            $this->postDeleteHook($category);

            return parent::delete($id);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute($this->getRedirectRoute());
        }

    }

    // Customer Interface Category Page

    #[Route('/category/{page<\d+>}',name:'app_category')]

    public function userCategory(int $page=1){

        // fetches queryBuyilder that returns all categories
        $qb = $this->categoryRepository->getAllQueryBuilder();

        // pagination setup
        $pagination = parent::getPagination($qb, $page, 10);
        $this->setTemplateData(['pager' => $pagination]);

        $this->setTemplateName('category/index.html.twig');
        $this->setRedirectRoute('app_main');
        return parent::read();
    }

    // User UI Page for single category

    #[Route('category/single/{id}/{page}',name:'category_view',requirements:['id' => '\d+'] )]
    public function viewCategory( int $id,int $page =1){

        // query builder to get products for the specific category
        $qb= $this->productRepository->getByCategoriesQuery($id);
        $pagination = parent::getPagination($qb, $page, 5);

        // getting dtaa for category
        $category =  $this->categoryRepository->findOneById($id);
        $this->setTemplateData(['pager' => $pagination,'category'=>$category]);

        $this->setTemplateName('category/single.html.twig');

        return parent::read();
    }
}
