<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TutosController extends AbstractController
{
    #[Route('/tutos', name: 'app_tutos')]
    public function index(): Response
    {
        return $this->render('tutos/index.html.twig', [
            'controller_name' => 'TutosController',
        ]);
    }
}
