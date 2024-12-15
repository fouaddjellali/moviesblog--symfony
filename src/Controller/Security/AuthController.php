<?php

declare(strict_types=1);

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * Affiche la page de connexion.
     *
     * @Route(path: '/login', name: 'login', methods: ['GET'])
     */
    public function login(): Response
    {
        return $this->render('auth/login.html.twig');
    }

    /**
     * Affiche la page d'inscription.
     *
     * @Route(path: '/register', name: 'register', methods: ['GET'])
     */
    public function register(): Response
    {
        return $this->render('auth/register.html.twig');
    }

    /**
     * Affiche la page pour réinitialiser le mot de passe.
     *
     * @Route(path: '/forgot', name: 'forgot', methods: ['GET'])
     */
    public function forgot(): Response
    {
        return $this->render('auth/forgot.html.twig');
    }

    /**
     * Affiche la page de confirmation d'inscription.
     *
     * @Route(path: '/confirm', name: 'confirm', methods: ['GET'])
     */
    public function confirm(): Response
    {
        return $this->render('auth/confirm.html.twig');
    }

    /**
     * Affiche la page de réinitialisation du mot de passe.
     *
     * @Route(path: '/reset', name: 'reset', methods: ['GET'])
     */
    public function reset(): Response
    {
        return $this->render('auth/reset.html.twig');
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     *
     * @Route(path: '/logout', name: 'logout', methods: ['GET'])
     */
    public function logout(): RedirectResponse
    {
        // Redirection vers la page d'accueil après déconnexion
        return $this->redirectToRoute('homepage');
    }
}
