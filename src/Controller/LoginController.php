<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Security\Model\Authenticator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authUtils): Response
    {
        // AuthenticationUtils est une dépendance relativement auto suffisante
        // on a besoin de géréer les erreurs de connexion
        $error = $authUtils->getLastAuthenticationError();

        // Si pas d'erreur, on récupère le dernier nom d'utilisateur
        // si mauvais mdp, le user n'aura pas besoin de retaper son pseudo/mail

        $lastUsername = $authUtils->getLastUsername();




        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
