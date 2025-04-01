<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Services\FileUploadService;
use App\Services\PaginatorServiceInterface;
use App\Services\ProductEventDispatcherService;
use App\Services\ProductServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Summary of ProductController
 * Route for product related operations
 */
class ProductController extends AbstractCRUDController
{
    public function __construct(
        private ProductServiceInterface $productService,
        private FileUploadService $fileUploadService,
        private ProductEventDispatcherService $eventDispatcherService,
        private PaginatorServiceInterface $paginatorService
    ) {
        parent::__construct($this->paginatorService);
    }

    public function getEntityName(): string
    {
        return "Product";
    }
    // get form type
    public function getFormType(): string
    {
        return ProductFormType::class;
    }

    // returns service
    public function getEntityServiceType(): ProductServiceInterface
    {
        return $this->productService;
    }

    // returns upload dir
    public function getUploadDir(): string
    {
        return $this->getParameter('kernel.project_dir') . '/public/assets/images/uploads';
    }
    /**
     * Hook for post-create actions.
     */
    protected function postCreateHook(object $entity): void
    {
        $this->eventDispatcherService->dispatchProductCreatedEvent($entity);
    }

    /**
     * Hook for post-update actions.
     */
    protected function postUpdateHook(object $entity): void
    {
        $this->eventDispatcherService->dispatchProductUpdatedEvent($entity);
    }

    /**
     * Hook for post-delete actions.
     */
    protected function postDeleteHook(object $entity): void
    {
        $this->eventDispatcherService->dispatchProductDeletedEvent($entity);
    }


    // display page
    #[Route('/admin/product/{page<\d+>}', name: 'admin_product')]
    public function index(int $page = 1): Response
    {
        $this->setTemplateName('admin/product/index.html.twig');

        $qb = $this->productService->getAllQueryBuilder();
        $pagination = $this->getPagination($qb, $page, 10);

        $this->setTemplateData(['pager' => $pagination]);

        return parent::read();
    }

    // display page for single item
    #[Route(path: '/admin/product/view/{id}', name:"admin_product_view", requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function singleProductPage(int $id): Response
    {
        $product = $this->productService->getOneById($id);

        $this->setTemplateName('admin/product/show.html.twig');
        $this->setTemplateData(['product' => $product]);

        return parent::read();
    }

    // add new product
    #[Route('/admin/product/create', name: 'admin_product_create', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $this->setTemplateName('admin/product/create.html.twig');
        $this->setRedirectRoute('admin_product');


        $product = new Product();
        $this->setData($product);

        $form = $this->createForm($this->getFormType(), $this->getData());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $imagePath = $form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = $this->fileUploadService->upload($imagePath, $this->getUploadDir());
                $entity->setImagePath('/assets/images/uploads/' . $newFileName);
            }
            try {
                $this->productService->save($entity);
                $this->postCreateHook($entity);
                $this->addFlash(static::SUCCESS, $this->getEntityName() . " added.");
                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (\Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());
            }
        }

        $this->setTemplateData(['form' => $form->createView(), 'product' => $product]);

        return parent::read();
    }

    #[Route('/admin/product/edit/{id}', name:'admin_product_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $this->setTemplateName('admin/product/edit.html.twig');
        $this->setRedirectRoute('admin_product');

        $product = $this->productService->getProduct($id);
        $originalImagePath = $product->getImagePath();

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = $this->fileUploadService->upload($imagePath, $this->getUploadDir());
                $product->setImagePath('/assets/images/uploads/' . $newFileName);
            } else {
                $product->setImagePath($originalImagePath);
            }

            try {
                $this->productService->getRepository()->save($product);
                $this->postUpdateHook($product);

                $this->addFlash('success', 'Product updated successfully.');
                return $this->redirectToRoute('admin_product');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        $this->setTemplateData(['form' => $form->createView(),'product'=>$product]);
        return parent::read();
    }

    // delete a single product
    #[Route('/admin/product/delete/{id}', name:"admin_product_delete", requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteProduct(int $id): Response
    {
        $this->setRedirectRoute('admin_product');
        try {
            $product = $this->productService->getOneById($id);
            //create log in dynamo db
            $this->postDeleteHook($product);
            return parent::delete($id);
        } catch (\Exception $e) {
            $this->addFlash(static::ERROR, $e->getMessage());
            return $this->redirectToRoute($this->getRedirectRoute());
        }
    }
    // User page for products
    #[Route('/product/{page<\d+>}', name: 'app_product')]
    public function userProducts(int $page = 1): Response
    {
        $this->setTemplateName('product/index.html.twig');

        $qb = $this->productService->getAllQueryBuilder();
        $pagination = $this->getPagination($qb, 1, 10);

        $this->setTemplateData(['pager' => $pagination]);


        return parent::read();
    }

    // User page for single product
    #[Route('/product/show/{id<\d+>}', name: 'app_product_view')]
    public function userSingleProduct(int $id): Response
    {
        $this->setTemplateName('product/show.html.twig');

        $product = $this->productService->getOneById($id);

        $this->setTemplateData(['product' => $product]);

        return parent::read();
    }


}
