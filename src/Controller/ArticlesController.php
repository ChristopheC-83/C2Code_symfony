<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Comments;
use App\Entity\User;
use App\Repository\ArticlesRepository;
use App\Repository\CommentsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArticlesController extends AbstractController
{
    private ArticlesRepository $articlesRepository;
    private CommentsRepository $commentsRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ArticlesRepository $articlesRepository,
        CommentsRepository $commentsRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->articlesRepository = $articlesRepository;
        $this->commentsRepository = $commentsRepository;
        $this->entityManager = $entityManager;
    }


    #[Route('/articles', name: 'app_articles')]
    public function index(): Response
    {
        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticlesController',
        ]);
    }

    #[Route('/article-comment', name: 'post_comment', methods: ['POST'])]
    public function addComment(Request $request): Response
    {
        // dd($request->request->all());        
        // dd($request->request->get('pseudo'), $request->request->get('user_id')); 
        $articleId = $request->request->get('article_id');
        $article = $this->articlesRepository->find($articleId);
        // Vérifie si l'article existe
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $comment = new Comments();

        // Si l'utilisateur est connecté, utilise son ID et pseudo
        if ($this->getUser()) {
            // Vérifie si le pseudo de l'utilisateur est défini
            $user = $this->getUser();
            if ($user && $user->getPseudo()) {
                $comment->setAuthor($user->getPseudo());
                $comment->setUser($user); // Assure-toi que setUser attend l'objet User
            } else {
                throw $this->createNotFoundException('L\'utilisateur n\'a pas de pseudo défini');
            }
        } else {
            // Sinon, récupère le pseudo du formulaire
            $pseudo = $request->request->get('pseudo');
            $comment->setAuthor($pseudo);
        }

        // Test si author est vide
        if (empty($comment->getAuthor())) {
            throw $this->createNotFoundException('Veuillez saisir un pseudo');
        }
        //  test si text vide
        if (empty($request->request->get('comment_text'))) {
            throw $this->createNotFoundException('Veuillez saisir un commentaire');
        }


        // Récupérer le texte du commentaire
        $commentText = $request->request->get('comment_text');
        $comment->setComment($commentText);

        // Lier le commentaire à l'article
        $comment->setArticle($article);

        // Enregistre le commentaire
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        // envoyer un mail à l'admin pour prévenir d'un nouveau commantaire
        $mail = new Mail();
        $mail->send('contact@ducompagnon.fr', 'Admin', 'Nouveau commentaire', 'new_comment.html', [
            'author' => $comment->getAuthor(),
            'comment' => $comment->getComment(),
            'article' => $article->getTitle()
        ]);



        // affiche d'un popup de validation
        $this->addFlash(
            'success',
            'Commentaire envoyé avec succés'
        );

        // Redirige vers la bonne route en fonction du type d'article
        $type = $request->request->get('type');
        switch ($type) {
            case 'projet':
                return $this->redirectToRoute('app_project_detail', ['id' => $articleId]); // Remplace par ta route
            case 'tuto':
                return $this->redirectToRoute('app_tuto_detail', ['id' => $articleId]); // Remplace par ta route
            case 'partage':
                return $this->redirectToRoute('app_share_detail', ['id' => $articleId]); // Remplace par ta route
            default:
                return $this->redirectToRoute('app_home'); // Redirection par défaut
        }
    }

    
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/comment/{id}/delete', name: 'delete_comment', methods: ['POST'])]
    public function deleteComment($id, Request $request): Response
    {
        $comment = $this->commentsRepository->find($id);

        if (!$comment) {
            throw $this->createNotFoundException('Commentaire non trouvé');
        }

        // Vérifie si l'utilisateur a le rôle ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions nécessaires.');
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();
        $this->addFlash(
            'info',
            'Commentaire supprimé avec succés'
        );

        // Redirige vers la bonne route en fonction du type d'article
        $type = $request->request->get('type');
        $articleId = $request->request->get('article_id');
        switch ($type) {
            case 'projet':
                return $this->redirectToRoute('app_project_detail', ['id' => $articleId]); // Remplace par ta route
            case 'tuto':
                return $this->redirectToRoute('app_tuto_detail', ['id' => $articleId]); // Remplace par ta route
            case 'partage':
                return $this->redirectToRoute('app_share_detail', ['id' => $articleId]); // Remplace par ta route
            default:
                return $this->redirectToRoute('app_home'); // Redirection par défaut
        }
    }
}
