<?php

namespace App\Controller\Creator;

use App\Form\NewArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WriteArticleController extends AbstractController
{
    #[Route('/creator/ecrire-nouvel-article', name: 'app_creator_write_new_article')]
    public function index(): Response
    {

            

        return $this->render(
            'creator/write_article/index.html.twig'
        );
    }
}
