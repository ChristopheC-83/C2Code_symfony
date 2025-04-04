<?php

namespace App\Controller;

use App\Repository\ArticlesRepository;
use App\Repository\CommentsRepository;
use App\Repository\FavoritesRepository;
use App\Repository\LanguagesRepository;
use App\Repository\TypesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectsController extends AbstractController
{
    
    #[Route('/projects', name: 'app_projects')]
    public function index(ArticlesRepository $articlesRepository, TypesRepository $typesRepository, LanguagesRepository $languagesRepository): Response
    {
        $meta_meta_description = "Des projets avec les principaux langages du dev web. Objectif : Pratiquer et apprendre en réalisant des projets concrets.";
        $articles = $articlesRepository->findBy(['visible' => 1, 'types' => 2]);
        $types = $typesRepository->findAll();
        $languages = $languagesRepository->findAll();
        return $this->render('projects/index.html.twig', [
            'types' => $types,
            'articles' => $articles,
            'languages' => $languages,
            'meta_description' => $meta_meta_description

        ]);
    }

    #[Route('/projects/{id}', name: 'app_project_detail')]
    public function detail($id, ArticlesRepository $articlesRepository, TypesRepository $typesRepository, LanguagesRepository $languagesRepository, UserRepository $userRepository, CommentsRepository $commentsRepository, FavoritesRepository $favoritesRepository): Response
    {
        
        $article = $articlesRepository->find($id);
        
        // article visible ?
        if(!$article || !$article->isVisible()){
            $this->addFlash('danger', 'Article non trouvé');
            return $this->redirectToRoute('app_projects');
        }

        $type = $typesRepository->find($article->getTypes()->getId());
        $language = $languagesRepository->find($article->getLanguages()->getId());
        $authorPseudo = $article->getAuthor();
        $author = $userRepository->findOneBy(['pseudo' => $authorPseudo]);
        $comments = $commentsRepository->findBy(['article' => $article]);
        $favorite = false;
        
        $title = $article->getTitle();
        $meta_meta_description = $title."un tutoriel qui pourrait sûrement vous intéresser !";
        if($favoritesRepository->findOneBy(['user' => $this->getUser(), 'article' => $id]) !=null){
            $favorite = true;
        }


        if (!$article || $article->getTypes()->getType() !== 'projet') {
            throw $this->createNotFoundException('Article non trouvé');
        }

        return $this->render('article/one_article.html.twig', [
            'article' => $article,
            'type' => $type,
            'language' => $language,
            'author' => $author,
            'comments' => $comments,
            'favorite' => $favorite,
            'meta_description' => $meta_meta_description
        ]);
    }
}
