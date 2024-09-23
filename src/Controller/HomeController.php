<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticlesRepository $articlesRepo): Response
    {
        $meta_description = 'Bienvenue chez le Compagnon de code. Faisons des projets pour valider vos connaissances en développement web.';

        $articles = $articlesRepo->findBy([], ['position' => 'DESC'], 3);

        $mail = new Mail();
        $vars = [
            'firstname' => 'kiki',
            'lastname' => 'san'
        ];

        if (
            $mail->send('kiketdule@gmail.com', 'kikisan', 'sujet test', 'welcome.html', $vars)
        ) {
            $this->addFlash(
                'success',
                'email envoyé !'
            );
        }

        return $this->render(
            'home/index.html.twig',
            [
                'meta_description' => $meta_description,
                'articles' => $articles
            ]
        );
    }
}
