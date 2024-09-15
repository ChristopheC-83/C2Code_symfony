<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Security\Model\Authenticator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function index(AuthenticationUtils $authUtils, Security $secu, UserRepository $userRepo): Response
    {


        // une fois connecté, on ne peut plus accéder à la page de connexion
        if ($secu->getUser()) {
            return $this->redirectToRoute('app_home'); // Rediriger vers la page d'accueil ou une autre route
        }

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


    #[Route('/deconnexion', name: 'app_logout', methods: ['GET'])]
    public function logout(): never
    {
        throw new \LogicException('Tu as bien mis logout dans ton fichier security.yaml !?!');
    }
   
}
//  A mettre dans security.yaml / main :
                // form_login:
                //     login_path: app_login
                //     check_path: app_login
                // logout:
                //     path : app_logout