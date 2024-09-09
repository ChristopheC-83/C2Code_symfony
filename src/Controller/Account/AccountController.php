<?php

namespace App\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }


    #[Route('/compte/modifier-mdp', name: 'app_account_modify_password')]
    public function password(): Response
    {
        return $this->render('account/index.html.twig');
    }
}
