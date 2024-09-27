<?php

namespace App\Controller\Admin;

use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MessagesController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/messages', name: 'app_admin_messages')]
    public function index(CommentsRepository $commentsRepository): Response
    {
        $commentsArray = $commentsRepository->findAll();

        // dd($commentsArray);

        return $this->render('admin/messages/index.html.twig', [
            'comments' => $commentsArray,
        ]);
    }
}
