<?php

namespace App\Controller;

use App\Entity\Product;
use App\Event\Events\ProductCRUDEvent;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Services\ProductService;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function Cake\Core\toString;

class ProductController extends AbstractFormController
{
    public function __construct(private ProductService $productService, private ProductRepository $productRepository, private EventDispatcherInterface $eventDispatcher)
    {
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

    // display page
    #[Route('/admin/product/{page<\d+>}', name: 'app_admin_product')]
    public function index(int $page = 1): Response
    {
        $this->setTemplateName('admin/product/index.html.twig');

        // query builder for pagination : gets All products
        $qb = $this->productRepository->getAllQueryBuilder();

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
        $this->setMessage(' New product added successfully');

        $product = new Product();
        $this->setData($product);

        $this->form = $this->createForm($this->getFormType(), $this->getData());
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $entity = $this->form->getData();
            $imagePath = $this->form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = $this->getService()->processUpload($imagePath, $this->getUploadDir());
                $entity->setImagePath('./images/uploads/' . $newFileName);
            }
            try {
                $this->getService()->add($entity);
                $this->addFlash(static::SUCCESS, $this->getMessage());

                //create log in dynamo db
                $event = new ProductCRUDEvent($entity, 'Create');
                $this->eventDispatcher->dispatch($event, ProductCRUDEvent::class);

                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (\Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());

                return $this->redirectToRoute($this->getRedirectRoute());
            }
        }

        $this->setTemplateData(['form' => $this->form->createView(), 'product' => $product]);

        return parent::read();
    }

    #[Route('/admin/product/edit/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editProduct(int $id, Request $request)
    {
        $this->setTemplateName('admin/product/edit.html.twig');
        $this->setRedirectRoute('app_admin_product');
        $this->setMessage('Product Updated successfully');

        $product = $this->productService->getOneById($id);
        $originalImagePath = toString($product->getImagePath());

        $this->form = $this->createForm(ProductFormType::class, $product);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $editedProduct = $this->form->getData();

            $imagePath = $this->form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/assets/images/uploads',
                        $newFileName
                    );

                    $editedProduct->setImagePath("./images/uploads/{$newFileName}") ;
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }

            if (null == $imagePath) {
                $editedProduct->setImagePath($originalImagePath);
            }

            try {
                $this->productService->edit($editedProduct);

                //create log in dynamo db
                $event = new ProductCRUDEvent($product, 'Update');
                $this->eventDispatcher->dispatch($event, ProductCRUDEvent::class);

                return $this->redirectToRoute($this->getRedirectRoute());
            } catch (\Exception $e) {
                $this->addFlash(static::ERROR, $e->getMessage());
                return $this->redirectToRoute($this->getRedirectRoute());
            }
        }
        $this->setTemplateData(['form' => $this->form, 'product' => $product]);

        return parent::read();
    }

    // delete a single product
    #[Route('/admin/product/delete/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteProduct(int $id): Response
    {
        $this->setRedirectRoute('app_admin_product');
        $this->setMessage('Product with id ' . $id . 'deleted successfully');


        try {
            $product = $this->productRepository->findOneById($id);
            //create log in dynamo db
            $event = new ProductCRUDEvent($product, 'Delete');
            $this->eventDispatcher->dispatch($event, ProductCRUDEvent::class);
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

        $qb = $this->productRepository->getAllQueryBuilder();
        $pagination = new Pagerfanta(
            new QueryAdapter($qb)
        );

        $pagination->setMaxPerPage(12);
        $pagination->setCurrentPage($page);

        $this->setTemplateData(['pager' => $pagination]);


        return parent::read();
    }

    // User page for single product
    #[Route('/product/single/{id<\d+>}', name: 'app_single_product')]
    public function userSingleProduct(int $id): Response
    {
        $this->setTemplateName('product/single.html.twig');

        $product = $this->getService()->getOneById($id);

        $this->setTemplateData(['product' => $product]);

        return parent::read();
    }
}
