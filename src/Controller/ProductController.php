<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Cake\Core\toString;

class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService)
    {
    }

    #[Route('/admin/product', name: 'admin_product')]
    public function index(): Response
    {
        $products = $this->productService->getAll();

        return $this->render('admin/product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route(path: '/admin/product/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function singleItem(int $id)
    {
        $product = $this->productService->getOneById($id);

        if (null == $product->getImagePath()) {
            $product->setImagePath('./images/no_image.png');
        }

        return $this->render('admin/product/singleProduct.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/admin/product/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $newProduct = new Product();

        $form = $this->createForm(ProductFormType::class, $newProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newProduct = $form->getData();
            $newProduct->setCategoryId($newProduct->getCategory()->getId());

            $imagePath = $form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = uniqid().'.'.$imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir').'/assets/images/uploads', $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newProduct->setImagePath('./images/uploads/'.$newFileName);
            }

            $this->productService->add($newProduct);

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('admin/product/create.html.twig', ['product' => $newProduct, 'form' => $form->createView()]);
    }

    #[Route('/admin/product/edit/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function editProduct($id, Request $request)
    {
        $product = $this->productService->getOneById($id);
        $originalImagePath = toString($product->getImagePath());
        // dd($originalImagePath);
        // exit;
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $product->setName($form->get('name')->getData());
            // $product->setDescription($form->get('description')->getData());
            // $product->setPrice($form->get('price')->getData());
            // $product->setAvailable($form->get('available')->getData());
            // $product->setCategory($form->get('category')->getData());
            // $product->setCategoryId($product->getCategory()->getId());
            $product->setImagePath($originalImagePath);

            $imagePath = $form->get('imagePath')->getData();

            if (null !== $imagePath) {
                $newFileName = uniqid().'.'.$imagePath->guessExtension();

                try {
                    $imagePath->move($this->getParameter('kernel.project_dir').'/assets/images/uploads', $newFileName);
                    $product->setImagePath('./images/uploads/'.$newFileName);
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
            }

            $this->productService->edit($product);

            return $this->redirectToRoute('admin_product');
        }

        return $this->render('/admin/product/edit.html.twig', ['product' => $product, 'form' => $form->createView()]);
    }

    // delete a single product
    #[Route('/admin/product/delete/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function delete($id)
    {
        $product = $this->productService->getOneById($id);
        $this->productService->delete($product);

        return $this->redirectToRoute('admin_product');
    }
}
