<?php

declare(strict_types=1);

namespace App\Controller\Movie;

use App\Entity\Category;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/discover', name: 'movie_discover', methods: ['GET'])]
    public function listCategories(
        CategorieRepository $categoryRepository
    ): Response {
        $categories = $categoryRepository->findAll();

        return $this->render('movie/discover.html.twig', [
            'categories' => $categories,
            'pageTitle' => 'Discover Categories',
        ]);
    }

    #[Route('/category/{id}', name: 'show_category', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showCategory(
        Category $category
    ): Response {
        return $this->render('movie/category.html.twig', [
            'category' => $category,
            'pageTitle' => sprintf('Category: %s', $category->getName()),
        ]);
    }
}
