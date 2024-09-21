<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\LanguagesRepository;
use App\Repository\TypesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'app_projects')]
    public function index(ArticlesRepository $articlesRepository, TypesRepository $typesRepository, LanguagesRepository $languagesRepository): Response
    {
        $meta_meta_description = "Des projets avec les principaux langages du dev web. Objectif : Pratiquer et apprendre en réalisant des projets concrets.";
        $articles = $articlesRepository->findByType('projet');
        $types = $typesRepository->findAll();
        $languades = $languagesRepository->findAll();
        return $this->render('projects/index.html.twig', [
            'types' => $types,
            'articles' => $articles,
            'languages' => $languades,
            'meta_description' => $meta_meta_description

        ]);
    }
}
