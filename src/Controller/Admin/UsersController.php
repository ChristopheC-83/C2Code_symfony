<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UsersController extends AbstractController
{
    #[Route('/admin/gestion-utilisateurs', name: 'app_admin_users')]
    public function index(UserRepository $UserRepo): Response
    {
        $users = $UserRepo->findAll();
        // dd($users);

        return $this->render('admin/users/index.html.twig',['users'=> $users]);
    }
}
