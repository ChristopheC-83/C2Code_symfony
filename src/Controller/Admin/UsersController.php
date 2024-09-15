<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/admin/gestion-utilisateurs/modif-role', name: 'app_admin_update_role')]
    public function updateRole(Request $request, EntityManagerInterface $em): Response
    {
        $userId = $request->request->get('user_id');
        $role = $request->request->get('role');
        $user = $em->getRepository(User::class)->find($userId);

        if ($user) {
            $user->setRoles([$role]);
            $em->flush();
            $this->addFlash('success', 'Le rôle a été modifié avec succès.');
        } else {
            $this->addFlash('error', 'Utilisateur non trouvé.');
        }

        return $this->redirectToRoute('app_admin_users');
    }
}
