<?php

declare(strict_types=1);

namespace App\Controller\Other;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    /**
     * Affiche le formulaire d'upload.
     *
     * @Route(path: '/upload', name: 'photo_upload', methods: ['GET'])
     */
    public function upload(): Response
    {
        // Retourne la vue pour l'upload des photos
        return $this->render('other/upload.html.twig');
    }
}
/**
 * Traite les fichiers téléchargés.
 *
 * @Route(path: '/upload', name: 'photo_upload_process', methods: ['POST'])
 */
public function processUpload(Request $request): Response
{
    $uploadedFile = $request->files->get('photo');
    if ($uploadedFile && $uploadedFile->isValid()) {
        $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($this->getParameter('upload_directory'), $newFilename);

        $this->addFlash('success', 'Photo téléchargée avec succès !');
        return $this->redirectToRoute('photo_upload');
    }

    $this->addFlash('error', 'Échec du téléchargement. Veuillez réessayer.');
    return $this->redirectToRoute('photo_upload');
}



