<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Services\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    // Page that displays the table of all the categories
    #[Route('admin/category', name: 'admin_category', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $categories = $this->categoryService->getAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // Page to add new category
    #[Route('/admin/category/new', methods: ['GET', 'POST'])]
    public function new(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->categoryService->add($category);

            return $this->redirectToRoute('admin_category');
        }

        return $this->render(
            '/admin/category/create.html.twig',
            ['category' => $category, 'form' => $form->createView()]
        );
    }

    // Page to edit a single category
    #[Route('/admin/category/edit/{id}', requirements: ['id' => '\d+'])]
    public function edit($id, Request $request): Response
    {
        $category = $this->categoryService->getOneById($id);
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->edit();

            return $this->redirectToRoute('admin_category');
        }

        return $this->render(
            '/admin/category/edit.html.twig',
            ['category' => $category, 'form' => $form->createView()]
        );
    }

    // Delete single Category
    #[Route('/admin/category/delete/{id}', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function delete($id)
    {
        $category = $this->categoryService->getOneById($id);
        $this->categoryService->delete($category);

        return $this->redirectToRoute('admin_category');
    }
}
