<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SharesController extends AbstractController
{
    #[Route('/shares', name: 'app_shares')]
    public function index(): Response
    {
        return $this->render('shares/index.html.twig', [
            'controller_name' => 'SharesController',
        ]);
    }
}
