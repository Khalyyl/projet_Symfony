<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'category_list')]
    public function list(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/add', name: 'category_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/edit/{id}', name: 'category_edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('category_list');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/delete/{id}', name: 'category_delete')]
    public function delete(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('category_list');
    }
}
