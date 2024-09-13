<?php

namespace App\Controller\Account;

use App\Form\NameUserType;
use App\Form\PasswordUserType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
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

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/modify_password.html.twig', [
            'modifyPwd' => $form->createView(),

        ]);
    }
    #[Route('/compte/modifier-pseudo', name: 'app_account_modify_pseudo')]
    public function modifyPseudo(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(NameUserType::class, $user);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->emi->flush();
                $newPseudo = $user->getPseudo();
                $this->addFlash('success', 'Votre pseudo a bien été modifié en : ' . $newPseudo . '');
                return $this->redirectToRoute('app_account');
            } catch (UniqueConstraintViolationException $e) {
                // Si le pseudo est déjà utilisé, on attrape l'exception
                $this->addFlash('danger', "Ce pseudo est déjà utilisé, merci d'en choisir un autre.");
                return $this->redirectToRoute('app_account_modify_pseudo');
            }
        }

        return $this->render('account/modify_pseudo.html.twig', [
            'modifyPwd' => $form->createView(),

        ]);
    }
    #[Route('/compte/modifier-avatar', name: 'app_account_modify_avatar')]
public function modifyAvatar(Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser();
    
    // Récupération des avatars
    $finder = new Finder();
    $finder->files()->in($this->getParameter('kernel.project_dir') . '/public/images/avatars');

    $avatars = [];
    foreach ($finder as $file) {
        $avatars[] = $file->getFilename();
    }

    // Si c'est une requête POST, on traite le formulaire
    if ($request->isMethod('POST')) {
        $newAvatar = $request->request->get('avatar');
        
        if ($newAvatar) {
            $user->setAvatar($newAvatar);
            $entityManager->flush();

            $this->addFlash('success', 'Votre avatar a bien été modifié.');
            return $this->redirectToRoute('app_account');
        }
    }

    // Si c'est une requête GET, on affiche le formulaire
    return $this->render('account/modify_avatar.html.twig', [
        'avatars' => $avatars,
        'userAvatar' => $user->getAvatar(),
    ]);
}
}
