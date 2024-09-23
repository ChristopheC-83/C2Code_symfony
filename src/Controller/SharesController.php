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

    #[Route('/shares/{id}', name: 'app_share_detail')]
    public function detail($id, ArticlesRepository $articlesRepository, TypesRepository $typesRepository, LanguagesRepository $languagesRepository, UserRepository $userRepository, CommentsRepository $commentsRepository,FavoritesRepository $favoritesRepository): Response
    {
        $article = $articlesRepository->find($id);
        $type = $typesRepository->find($article->getTypes()->getId());
        $language = $languagesRepository->find($article->getLanguages()->getId());
        $authorPseudo = $article->getAuthor();
        $author = $userRepository->findOneBy(['pseudo' => $authorPseudo]);
        $comments = $commentsRepository->findBy(['article' => $article]);
        $favorite = false;
        if($favoritesRepository->findOneBy(['user' => $this->getUser(), 'article' => $id]) !=null){
            $favorite = true;
        }

        if (!$article || $article->getTypes()->getType() !== 'partage') {
            throw $this->createNotFoundException('Article non trouvé');
        }

        return $this->render('article/one_article.html.twig', [
            'article' => $article,
            'type' => $type,
            'language' => $language,
            'author' => $author,
            'comments' => $comments,
            'favorite' => $favorite,
        ]);
    }
}
