<?php

declare(strict_types=1);

namespace App\Controller\Movie;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MovieController extends AbstractController
{
    #[Route('/movie/{id}', name: 'show_movie', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showMovie(Movie $movie): Response
    {
        return $this->render('movie/detail.html.twig', [
            'movie' => $movie,
            'pageTitle' => sprintf('Film: %s', $movie->getTitle()),
        ]);
    }

    #[Route('/series', name: 'list_series', methods: ['GET'])]
    public function listSeries(): Response
    {
        // Vous pouvez ajouter des données fictives ou dynamiques ici si nécessaire.
        $series = [
            ['title' => 'Série 1', 'description' => 'Description de la série 1'],
            ['title' => 'Série 2', 'description' => 'Description de la série 2'],
        ];

        return $this->render('movie/list_series.html.twig', [
            'series' => $series,
            'pageTitle' => 'Liste des Séries',
        ]);
    }
}
