<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Services\FileUploadService;
use App\Services\ProductEventDispatcherService;
use App\Services\ProductService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Summary of ProductController
 * Route for product related operations
 */
class ProductController extends AbstractFormController
{
    public function __construct(
        private ProductService $productService,
        private FileUploadService $fileUploadService,
        private ProductEventDispatcherService $eventDispatcherService,
    ) {
    }

    // get form type
    public function getFormType(): string
    {
        return ProductFormType::class;
    }

    // returns service
    public function getService(): ProductService
    {
        return $this->productService;
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
    #[Route('/admin/product/{page<\d+>}', name: 'app_admin_product')]
    public function index(int $page = 1): Response
    {
        $this->setTemplateName('admin/product/index.html.twig');

        // query builder for pagination : gets All products
        $qb = $this->productService->getAllQueryBuilder();

        //setup pagination
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );
        $pagination->setMaxPerPage(10);
        $pagination->setCurrentPage($page);

        $this->setTemplateData(['pager' => $pagination]);

        return parent::read();
    }

    // display page for single item
    #[Route(path: '/admin/product/single/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function singleProductPage(int $id): Response
    {
        $product = $this->productService->getOneById($id);

        $this->setTemplateName('admin/product/singleProduct.html.twig');
        $this->setTemplateData(['product' => $product]);

        return parent::read();
    }

    // add new product
    #[Route('/admin/product/create', name: 'app_admin_product_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $this->setTemplateName('admin/product/create.html.twig');
        $this->setRedirectRoute('app_admin_product');
        $this->setMessage(' New product added successfully.');

        $product = new Product();
        $this->setData($product);

        $this->form = $this->createForm($this->getFormType(), $this->getData());
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $entity = $this->form->getData();
            $imagePath = $this->form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = $this->fileUploadService->upload($imagePath, $this->getUploadDir());
                $entity->setImagePath('./images/uploads/' . $newFileName);
            }
            try {
                $this->getService()->add($entity);

                $this->postCreateHook($entity);
                $this->addFlash(static::SUCCESS, $this->getMessage());
                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (\Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());
            }
        }

        $this->setTemplateData(['form' => $this->form->createView(), 'product' => $product]);

        return parent::read();
    }

    #[Route('/admin/product/edit/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request): Response
    {
        $this->setTemplateName('admin/product/edit.html.twig');
        $this->setRedirectRoute('app_admin_product');

        $product = $this->productService->getOneById($id);
        $originalImagePath = $product->getImagePath();

        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = $this->fileUploadService->upload($imagePath, $this->getUploadDir());
                $product->setImagePath('./images/uploads/' . $newFileName);
            } else {
                $product->setImagePath($originalImagePath);
            }

            try {
                $this->productService->edit($product);
                $this->postUpdateHook($product);

                $this->addFlash('success', 'Product updated successfully.');
                return $this->redirectToRoute('app_admin_product');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        $this->setTemplateData(['form' => $form->createView()]);
        return parent::read();
    }

    // delete a single product
    #[Route('/admin/product/delete/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteProduct(int $id): Response
    {
        $this->setRedirectRoute('app_admin_product');
        $this->setMessage('Product with id ' . $id . 'deleted successfully');
        try {
            $product = $this->productService->getOneById($id);
            //create log in dynamo db
            $this->postDeleteHook($product);
            return parent::delete($product);
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
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage(12);
        $pagination->setCurrentPage($page);

        $this->setTemplateData(['pager' => $pagination]);


        return parent::read();
    }

    // User page for single product
    #[Route('/product/single/{id<\d+>}', name: 'product_view')]
    public function userSingleProduct(int $id): Response
    {
        $this->setTemplateName('product/single.html.twig');

        $product = $this->getService()->getOneById($id);

        $this->setTemplateData(['product' => $product]);

        return parent::read();
    }


}
