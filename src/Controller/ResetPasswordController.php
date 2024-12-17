<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    private ResetPasswordHelperInterface $resetPasswordHelper;
    private EntityManagerInterface $entityManager;
    private RequestStack $requestStack;

    public function __construct(
        ResetPasswordHelperInterface $resetPasswordHelper,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    ) {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    /**
     * Affiche un formulaire permettant à l'utilisateur de saisir son email
     * pour générer un token de réinitialisation du mot de passe.
     */
    #[Route('', name: 'app_forgot_password_request', methods: ['GET', 'POST'])]
    public function request(Request $request, UserRepository $userRepository,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $userRepository,
                $mailer,
                $urlGenerator
            );
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * Génère un token et l'affiche directement dans la réponse.
     * Aucun email n'est envoyé.
     */
    private function processSendingPasswordResetEmail(string $email, UserRepository $userRepository,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator): Response
    {
        $user = $userRepository->findOneBy(['email' => $email]);

        // On ne révèle pas si l'utilisateur n'existe pas
        if (!$user) {
            return new Response('Si un compte existe avec cet email, un lien de réinitialisation a été envoyé.');
        }

        try {
            // Génération du token
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (\Throwable $e) {
            return new Response('Impossible de générer un token. Veuillez réessayer plus tard.');
        }

        // Génération du lien de réinitialisation
        $resetUrl = $urlGenerator->generate(
            'app_reset_password', // Nom de la route pour le traitement de la réinitialisation
            ['token' => $resetToken->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        // Création de l'email
        $emailMessage = (new Email())
            ->from('no-reply@example.com') // Adresse de l'expéditeur
            ->to($email) // Adresse du destinataire
            ->subject('Réinitialisation de votre mot de passe')
            ->html('<p>Bonjour,</p>
                <p>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant :</p>
                <p><a href="' . $resetUrl . '">Réinitialiser mon mot de passe</a></p>
                <p>Si vous n\'avez pas fait cette demande, ignorez cet email.</p>');

        // Envoi de l'email
        $mailer->send($emailMessage);

        return new Response('Un lien de réinitialisation a été envoyé à votre adresse email.');
    }

    /**
     * Page de réinitialisation du mot de passe à partir d'un token.
     * Normalement accessible via /reset-password/reset/{token}.
     */
    #[Route('/reset/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, string $token = null): Response
    {
        if ($token) {
            $this->storeTokenInSession($token);
            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            return $this->redirectToRoute('app_forgot_password_request');
        }

        try {
            /** @var User $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (\Throwable $e) {
            $this->addFlash('reset_password_error', sprintf(
                'Il y a eu un problème avec votre demande de réinitialisation : %s',
                $e->getMessage()
            ));
            return $this->redirectToRoute('app_forgot_password_request');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($encodedPassword);

            $this->entityManager->flush();

            // Invalider le token utilisé
            $this->resetPasswordHelper->removeResetRequest($token);

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');

            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    
    private function storeTokenInSession(string $token): void
    {
        $this->requestStack->getCurrentRequest()->getSession()->set('ResetPasswordToken', $token);
    }

    private function getTokenFromSession(): ?string
    {
        return $this->requestStack->getCurrentRequest()->getSession()->get('ResetPasswordToken');
    }
}
