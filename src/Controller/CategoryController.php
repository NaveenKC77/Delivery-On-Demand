<?php

namespace App\Controller;

use App\Entity\Category;
use App\Event\Events\CategoryCRUDEvent;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Services\CategoryService;
use App\Services\ServicesInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractFormController
{
    public function __construct(
        private CategoryService $categoryService,
        private CategoryRepository $categoryRepository,
        private EventDispatcherInterface $eventDispatcher
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
                $event = new CategoryCRUDEvent($this->data, 'Create');
                $this->eventDispatcher->dispatch($event, CategoryCRUDEvent::class);

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
                $event = new CategoryCRUDEvent($this->getData(), 'Update');
                $this->eventDispatcher->dispatch($event, CategoryCRUDEvent::class);

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
            $event = new CategoryCRUDEvent($category, 'Create');
            $this->eventDispatcher->dispatch($event, CategoryCRUDEvent::class);

            return parent::delete($id);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute($this->getRedirectRoute());
        }

    }
}
