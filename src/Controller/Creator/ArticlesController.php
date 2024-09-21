<?php

namespace App\Controller\Creator;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Repository\LanguagesRepository;
use App\Repository\TypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/creator/articles')]
final class ArticlesController extends AbstractController
{
    #[Route(name: 'app_creator_articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository, LanguagesRepository $langRepo, Security $security, TypesRepository $typesRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        $pseudo = $user ? $user->getPseudo() : '';
        $articles=[];
        $types = $typesRepository->findAll();

        // Vérifier si l'utilisateur est "Christophe_C"
        if ($pseudo === 'Christophe_C') {
            // Récupère tous les articles triés par position croissante
            $articles = $articlesRepository->findBy([], ['position' => 'ASC']);
        } else {
            // Récupère les articles filtrés par l'auteur connecté, triés par position croissante
            $articles = $articlesRepository->findBy(['author' => $pseudo], ['position' => 'ASC']);
        }
        $articles_tuto = array_filter($articles, function($article) {
            return $article->getTypes()->getType() === 'tuto';
        });
        $articles_projet = array_filter($articles, function($article) {
            return $article->getTypes()->getType() === 'projet';
        });
        $articles_partage = array_filter($articles, function($article) {
            return $article->getTypes()->getType() === 'partage';
        });

        $languages = $langRepo->findAll();

        return $this->render('creator/articles/index.html.twig', [
            'articles' => $articles,
            'articles_tuto' => $articles_tuto,
            'articles_projet' => $articles_projet,
            'articles_partage' => $articles_partage,
            'languages' => $languages,
            'types' => $types,
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
        $form->add('author', TextType::class, [
            'disabled' => $user && $user->getPseudo() === 'Christophe_C' ? false : true,  // Condition pour rendre le champ modifiable
            'label' => 'Auteur',
        ]);
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
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager,  Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        $pseudo = $user ? $user->getPseudo() : '';

        // Vérifier si l'utilisateur est autorisé à modifier cet article
        if ($pseudo !== 'Christophe_C' && $article->getAuthor() !== $pseudo) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cet article.');
        }

        $form = $this->createForm(ArticlesType::class, $article);

        // Modifier le formulaire pour désactiver le champ 'author' sauf pour Christophe_C
        $form->add('author', TextType::class, [
            'disabled' => $pseudo !== 'Christophe_C',  // Rendre le champ non modifiable si l'utilisateur n'est pas Christophe_C
            'label' => 'Auteur',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_creator_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creator/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_creator_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_creator_articles_index', [], Response::HTTP_SEE_OTHER);
    }
}
