<?php

namespace App\Controller\Admin;

use App\Repository\CreditsPurchaseRegisterRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PurchasesLogController extends AbstractController
{


    private  $creditsPurchaselog;
    private $userRepository;

    public function __construct(
        CreditsPurchaseRegisterRepository $creditsPurchaselog,
        UserRepository $userRepository
    ) {
        $this->creditsPurchaselog = $creditsPurchaselog;
        $this->userRepository = $userRepository;
    }

    #[Route('/admin/purchases-log/{pseudo}', name: 'app_admin_purchases_log', defaults: ['pseudo' => null])]
    public function index($pseudo): Response
    {
        $selectedUser = null;

        $users = $this->userRepository->findAll();

        if (!$pseudo) {
            $purchases = $this->creditsPurchaselog->findAll();
        } else {
            $selectedUser = $this->userRepository->findOneBy(['pseudo' => $pseudo]);
            if (!$selectedUser) {
                throw $this->createNotFoundException('User not found');
            }
            $purchases = $this->creditsPurchaselog->findBy(['user' => $selectedUser]);
        }

        // dd($purchases, $selectedUser);

        return $this->render('admin/purchases_log/index.html.twig', [
            'users' => $users,
            'purchases' => $purchases,
            'selectedUser' => $selectedUser
        ]);
    }
}
