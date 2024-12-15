<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route(path: '/', name: 'dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/homepage.html.twig', [
            'pageTitle' => 'Dashboard Admin',
        ]);
    }

    #[Route(path: '/movies', name: 'movies_list')]
    public function listMovies(): Response
    {
        return $this->render('admin/admin_films.html.twig', [
            'pageTitle' => 'Liste des films',
        ]);
    }

    #[Route(path: '/movies/add', name: 'movies_add')]
    public function addMovie(): Response
    {
        return $this->render('admin/admin_add_films.html.twig', [
            'pageTitle' => 'Ajouter un film',
        ]);
    }
}
