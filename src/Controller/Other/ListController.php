<?php

declare(strict_types=1);

namespace App\Controller\Other;

use App\Repository\PlaylistRepository;
use App\Repository\PlaylistSubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route(path: '/lists', name: 'show_my_list', methods: ['GET'])]
    public function show(
        PlaylistRepository $playlistRepository,
        PlaylistSubscriptionRepository $playlistSubscriptionRepository,
        Request $request
    ): Response {
        // Récupère l'ID de la playlist active
        $playlistId = $request->query->getInt('playlist', 0);

        // Recherche de la playlist active si un ID est fourni
        $activePlaylist = $playlistId > 0 ? $playlistRepository->find($playlistId) : null;

        if ($playlistId && !$activePlaylist) {
            $this->addFlash('warning', 'La playlist demandée est introuvable.');
        }

        // Récupération des données
        $playlists = $playlistRepository->findAll();
        $subscribedPlaylists = $playlistSubscriptionRepository->findAll();

        // Rendu du template
        return $this->render('other/lists.html.twig', [
            'playlists' => $playlists,
            'subscribedPlaylists' => $subscribedPlaylists,
            'activePlaylist' => $activePlaylist,
        ]);
    }
}
