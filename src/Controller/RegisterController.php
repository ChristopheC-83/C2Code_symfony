<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]

    // Request est une classe de Symfony qui permet de récupérer les données des requêtes HTTP
    // EntityManagerInterface est une classe de Doctrine qui permet de gérer les entités
    public function index(Request $request, EntityManagerInterface $emi, Security $secu): Response
    {
        // une fois connecté, on ne peut plus accéder à la page d'inscription
        if ($secu->getUser()) {
            return $this->redirectToRoute('app_home'); // Rediriger vers la page d'accueil ou une autre route
        }


        //  nous voulons créer un nouveau User
        $user = new User();

        // après avoir créé le fichier RegisterUserType dans le dossier Form et lié à l'objet à créer, ici User
        // nous créons le formulaire à afficher
        //  nous le passerons dans le render via la variable $form
        $form = $this->createForm(RegisterType::class, $user);

        // on écoute le formulaire à travers l'objet request
        $form->handleRequest($request);

        // on créé un mail d econfirmation à l'inscription
        $mail = new Mail();

        // Si le formulaire est soumis
        // On vérifie si le formulaire est valide
        // On récupère les données du formulaire
        //  on envoie une validation flash
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form -> getData();
            // dd($data);
            $mail->send(
                $data->getEmail(),
                $data->getPseudo(),
                'Bienvenue chez le Compagnon de code',
                'register.html',
                [
                    'pseudo' => $data->getPseudo(),
                ]
            );
            $user->setCreatedAt(new \DateTimeImmutable);
            $user->setCredits(0);
            $user -> setFirstPurchase(true);
            $emi->persist($user);
            $emi->flush();

            $this->addFlash('success', 'Votre inscription a bien été prise en compte, vous pouvez vous connecter !'); // 

            return $this->redirectToRoute('app_login'); 
        }




        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
