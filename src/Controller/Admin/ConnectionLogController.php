<?php

namespace App\Controller\Admin;

use App\Repository\CreditsPurchaseRegisterRepository;
use App\Repository\UserConnectionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



class ConnectionLogController extends AbstractController
{

    private  $creditsPurchaselog;
    private $userRepository;

    private $userConnectionRepository;

    public function __construct(
        CreditsPurchaseRegisterRepository $creditsPurchaselog,
        UserRepository $userRepository,
        UserConnectionRepository $userConnectionRepository
    ) {
        $this->creditsPurchaselog = $creditsPurchaselog;
        $this->userRepository = $userRepository;
        $this->userConnectionRepository = $userConnectionRepository;
    }


    // Controller.php

    #[Route('/admin/connection-log/{id}', name: 'app_admin_connection_log', defaults: ['id' => null])]
    public function index($id): Response
    {
        $selectedUser = null;
        $users = $this->userRepository->findAll();
        $ipCounts = $this->userConnectionRepository->countDistinctIpsByUser();

        // Récupération des connexions de manière ordonnée
        $connections = $this->userConnectionRepository->findConnectionsByUserOrderedByDate($id);

        

        // Si un ID est fourni, on définit l'utilisateur sélectionné
        if ($id) {
            $selectedUser = $this->userRepository->find($id);
        }

        // Retour de la vue avec les variables
        return $this->render('admin/connection_log/index.html.twig', [
            'users' => $users,
            'connections' => $connections,
            'selectedUser' => $selectedUser,
            'ipCounts' => $ipCounts
        ]);
    }
}
