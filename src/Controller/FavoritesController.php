<?php

namespace App\Controller;

use App\Entity\Favorites;
use App\Repository\ArticlesRepository;
use App\Repository\FavoritesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FavoritesController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/article/{id}/like', name: 'like_article', methods: ['POST'])]
    public function likeArticle($id, EntityManagerInterface $entityManager, ArticlesRepository $articlesRepository, Request $request): Response
    {
        $article = $articlesRepository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $favorite = new Favorites();
        $favorite->setUser($this->getUser());
        $favorite->setArticle($article);

        $entityManager->persist($favorite);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Article ajouté dans les Favoris'
        );

        // Utiliser l'en-tête HTTP "Referer" pour rediriger vers la page précédente
        $referer = $request->headers->get('referer');

        if ($referer) {
            return $this->redirect($referer);
        }

        // Si "Referer" n'est pas défini, rediriger vers une page par défaut
        return $this->redirectToRoute('app_home');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/article/{id}/unlike', name: 'unlike_article', methods: ['POST'])]
    public function unlikeArticle($id, EntityManagerInterface $entityManager, FavoritesRepository $favoritesRepository, Request $request): Response
    {
        $favorite = $favoritesRepository->findOneBy(['user' => $this->getUser(), 'article' => $id]);

        if ($favorite) {
            $entityManager->remove($favorite);
            $entityManager->flush();
            $this->addFlash(
                'warning',
                'Article retiré des Favoris'
            );
        }

        // Utiliser l'en-tête HTTP "Referer" pour rediriger vers la page précédente
        $referer = $request->headers->get('referer');

        if ($referer) {
            return $this->redirect($referer);
        }
        // Redirection ou message de succès
        return $this->redirectToRoute('app_home');
    }
}
