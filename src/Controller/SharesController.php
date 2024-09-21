<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\LanguagesRepository;
use App\Repository\TypesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class SharesController extends AbstractController
{
    #[Route('/shares', name: 'app_shares')]
    public function index(ArticlesRepository $articlesRepository, TypesRepository $typesRepository, LanguagesRepository $languagesRepository): Response
    {
        $meta_meta_description = "Des patages mêlant les différents langages du dev web. Objectif : Partager mes connaissances et mes découvertes.";
        
        $articles = $articlesRepository->findByType('partage');
        $types = $typesRepository->findAll();
        $languades = $languagesRepository->findAll();


        return $this->render('shares/index.html.twig', [
            'types' => $types,
            'articles' => $articles,
            'languages' => $languades,
            'meta_description' => $meta_meta_description
        ]);
    }
}
