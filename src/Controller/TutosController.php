<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\LanguagesRepository;
use App\Repository\TypesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TutosController extends AbstractController
{
    #[Route('/tutos', name: 'app_tutos')]
    public function index(ArticlesRepository $articlesRepository, TypesRepository $typesRepository, LanguagesRepository $languagesRepository): Response
    {

        $meta_meta_description = "Des tutoriels pour apprendre les principaux langages du dev web. Objectif : creuser une notion en particulier sans s'étaler.";
        $articles = $articlesRepository->findByType('tuto');
        $types = $typesRepository->findAll();
        $languades = $languagesRepository->findAll();

        return $this->render('tutos/index.html.twig', [
            'types' => $types,
            'articles' => $articles,
            'languages' => $languades,
            'meta_description' => $meta_meta_description
        ]);
    }
}
