<?php

namespace App\Controller\Account;

use App\Form\PasswordUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{

    private $emi;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
    }

    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/modifier-mdp', name: 'app_account_modify_password')]
    public function modifyPassword(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        // on récupère les données du user connecté pour les envoyer au formulaire
        $user = $this->getUser();  
        // on crée le formulaire lié à la classe PasswordUserType
        // on lui transfert user pour croiser les infos
        // on lui transfert passwordHasher pour hasher le mdp

        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher,
        ]);

        $form->handleRequest($request);

        // isValid provient de la classe PasswordUserType
        // au niveau du addEventListener, on a mis un listener sur le SUBMIT
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
            // Comme on update et pas create, on ne persist pas, le flush suffit
            $this->emi->flush();
        }

        return $this->render('account/modify_password.html.twig', [
            'modifyPwd' => $form->createView(),

        ]);
    }
}
