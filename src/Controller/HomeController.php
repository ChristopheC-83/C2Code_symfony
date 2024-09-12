<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $meta_description = 'Bienvenue chez le Compagnon de code. Faisons des projets pour valider vos connaissances en développement web.';



        return $this->render(
            'home/index.html.twig',
            [
                'meta_description' => $meta_description
            ]
        );
    }
}
