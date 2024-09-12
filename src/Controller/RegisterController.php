<?php

namespace App\Controller;

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

        // Si le formulaire est soumis
        // On vérifie si le formulaire est valide
        // On récupère les données du formulaire
        //  on envoie une validation flash
        if ($form->isSubmitted() && $form->isValid()) {
            // $data = $form -> getData();
            // dd($data);

            //  on fige les données
            $emi->persist($user);
            // on envoie les données en base
            $emi->flush();


            $this->addFlash('success', 'Votre inscription a bien été prise en compte, vous pouvez vous connectee !'); // message de confirmation de l'inscription

            return $this->redirectToRoute('app_login');  // redirection vers la page de connexion
        }




        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
