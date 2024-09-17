<?php

namespace App\Controller\Creator;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/creator/articles')]
final class ArticlesController extends AbstractController
{
    #[Route(name: 'app_creator_articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository): Response
{
    // Récupère les articles triés par position croissante
    $articles = $articlesRepository->findBy([], ['position' => 'ASC']);

    return $this->render('creator/articles/index.html.twig', [
        'articles' => $articles,
    ]);
}

    #[Route('/new', name: 'app_creator_articles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Articles();

          // Récupérer l'utilisateur connecté
          $user = $this->getUser();
        
          // Si l'utilisateur est connecté, définir son pseudo comme auteur
          if ($user) {
              $article->setAuthor($user->getPseudo()); // Assure-toi que l'entité Articles a une méthode setAuthor
          }

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_creator_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creator/articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creator_articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('creator/articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_creator_articles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_creator_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creator/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creator_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_creator_articles_index', [], Response::HTTP_SEE_OTHER);
    }
}
