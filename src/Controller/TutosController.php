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

class TutosController extends AbstractController
{
    
    #[Route('/tutos', name: 'app_tutos')]
    public function index(ArticlesRepository $articlesRepository, TypesRepository $typesRepository, LanguagesRepository $languagesRepository): Response
    {

        $meta_meta_description = "Des tutoriels pour apprendre les principaux langages du dev web. Objectif : creuser une notion en particulier sans s'étaler.";
        $articles = $articlesRepository->findBy(['visible' => 1, 'types' => 3]);
        $types = $typesRepository->findAll();
        $languages = $languagesRepository->findAll();

        return $this->render('tutos/index.html.twig', [
            'types' => $types,
            'articles' => $articles,
            'languages' => $languages,
            'meta_description' => $meta_meta_description
        ]);
    }

    #[Route('/tutos/{id}', name: 'app_tuto_detail')]
    public function detail($id, ArticlesRepository $articlesRepository, TypesRepository $typesRepository, LanguagesRepository $languagesRepository, UserRepository $userRepository,CommentsRepository $commentsRepository,FavoritesRepository $favoritesRepository): Response
    {
        $article = $articlesRepository->find($id);
        
        // article visible ?
        if(!$article || !$article->isVisible()){
            $this->addFlash('danger', 'Article non trouvé');
            return $this->redirectToRoute('app_tutos');
        }

        $type = $typesRepository->find($article->getTypes()->getId());
        $language = $languagesRepository->find($article->getLanguages()->getId());
        $authorPseudo = $article->getAuthor();
        $author = $userRepository->findOneBy(['pseudo' => $authorPseudo]);
        $comments = $commentsRepository->findBy(['article' => $article]);
        $favorite = false;
        // dd($article);
        $title = $article->getTitle();
        $meta_meta_description = $title."un tutoriel qui pourrait sûrement vous intéresser !";

        if($favoritesRepository->findOneBy(['user' => $this->getUser(), 'article' => $id]) !=null){
            $favorite = true;
        }


        if (!$article || $article->getTypes()->getType() !== 'tuto') {
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
