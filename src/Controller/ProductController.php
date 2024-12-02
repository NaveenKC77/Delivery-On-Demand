<?php

namespace App\Controller;

use App\Controller\AbstractFormController;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Services\ProductService;
use App\Services\ServicesInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use function Cake\Core\toString;

class ProductController extends AbstractFormController
{

    public function __construct(private ProductService $productService, private ProductRepository $productRepository)
    {
        parent::__construct();
    }

    //get form type
    public function getFormType(): string
    {

        return ProductFormType::class;
    }

    //returns service
    public function getService(): ServicesInterface
    {
        return $this->productService;
    }

    //returns upload dir 
    public function getUploadDir(): string
    {
        return
            $this->getParameter('kernel.project_dir') . '/assets/images/uploads';
    }

    // display page
    #[Route('/admin/product/{page<\d+>}', name: 'app_admin_product')]
    public function index($page = 1): Response
    {
        $this->setTemplateName('admin/product/index.html.twig');

        $qb = $this->productRepository->getAllQueryBuilder();
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

        if ($product->getImagePath() == null) {
            $product->setImagePath('./images/no_image.png');
        }
        $this->setTemplateName('admin/product/singleProduct.html.twig');
        $this->setTemplateData(['product' => $product]);
        return parent::read();
    }

    //add new product
    #[Route('/admin/product/create', name: 'app_admin_product_create', methods: ['GET', 'POST'])]
    public function createProduct(Request $request): Response
    {
        $this->setTemplateName('admin/product/create.html.twig');
        $this->setRedirectRoute('admin_product');

        $newProduct = new Product();

        $this->form = $this->createForm(ProductFormType::class, $newProduct);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $newProduct = $this->form->getData();
            $imagePath = $this->form->get('imagePath')->getData();


            // move uploaded image to uploads, also change name , to provide unique names
            if ($imagePath) {
                $newFileName = $this->getService()->processUpload($imagePath, $this->getUploadDir());
                $newProduct->setImagePath('./images/uploads/' . $newFileName);
            }

            $this->getService()->add($newProduct);
            $this->addFlash('success', 'Product ' . $newProduct->getName() . ' successfully added');

            return $this->redirectToRoute($this->getRedirectRoute());
        }

        $this->setTemplateData(['form' => $this->form->createView(), 'product' => $newProduct]);

        return parent::read();
    }

    #[Route('/admin/product/edit/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editProduct($id, Request $request)
    {
        $this->setTemplateName('admin/product/edit.html.twig');
        $this->setRedirectRoute('admin_product');

        $product = $this->productService->getOneById($id);
        $originalImagePath = toString($product->getImagePath());

        $this->form = $this->createForm(ProductFormType::class, $product);
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $product->setImagePath($originalImagePath);

            $imagePath = $this->form->get('imagePath')->getData();


            if (null !== $imagePath) {
                $newFileName = $this->getService()->processUpload($imagePath, $this->getUploadDir());
                $product->setImagePath('./images/uploads/' . $newFileName);
            }

            $this->productService->edit($product);
            $this->addFlash('success', 'Product successfully edited.');

            return $this->redirectToRoute($this->getRedirectRoute());
        }


        $this->setTemplateData(['form' => $this->form->createView(), 'product' => $product]);

        return parent::read();
    }

    // delete a single product
    #[Route('/admin/product/delete/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function deleteProduct($id): Response
    {
        $this->setRedirectRoute('admin_product');
        $this->addFlash('error', 'Product successfully deleted.');
        return parent::delete($id);
    }


    //User page for products
    #[Route('/product/{page<\d+>}', name: 'app_product')]
    public function userProducts($page = 1): Response
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

    //User page for single product
    #[Route('/product/single/{id<\d+>}', name: 'app_single_product')]
    public function userSingleProduct($id): Response
    {
        $this->setTemplateName('product/single.html.twig');



        $product = $this->getService()->getOneById($id);

        $this->setTemplateData(['product' => $product]);
        return parent::read();
    }
}
