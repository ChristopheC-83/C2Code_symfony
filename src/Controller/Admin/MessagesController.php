<?php

namespace App\Controller\Admin;

use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MessagesController extends AbstractController
{
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
