<?php

namespace App\Controller;

use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(): Response
    {
        // après avoir créé le fichier RegisterUserType dans le dossier Form
        // nous créons le formulaire à afficher
        //  nous le passerons dans le rendervia la variable $form

        $form = $this -> createForm(RegisterType::class);






        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
