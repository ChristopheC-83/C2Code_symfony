<?php

namespace App\Controller\Admin;

use App\Repository\LessonsRepository;
use App\Repository\PremiumLessonsAccessRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PremiumAccessController extends AbstractController
{


    private $userRepository;
    private $premiumLessonsAccessRepository;
    private $lessonsRepository;

    public function __construct(
        UserRepository $userRepository,
        PremiumLessonsAccessRepository $premiumLessonsAccessRepository,
        LessonsRepository $lessonsRepository   ,
    ) {
        $this->userRepository = $userRepository;
        $this->premiumLessonsAccessRepository = $premiumLessonsAccessRepository;
        $this->lessonsRepository = $lessonsRepository;

    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/premium-access', name: 'app_admin_premium_access')]
    public function index(Request $request): Response
    {
        $selectedUser = $request->query->get('user');
        $selectedLesson = $request->query->get('lesson');
    
        $filters = [];
    
        if ($selectedUser) {
            $user = $this->userRepository->find($selectedUser);
            if ($user) {
                $filters['user'] = $user;
            }
        }
    
        if ($selectedLesson) {
            $lesson = $this->lessonsRepository->find($selectedLesson);
            if ($lesson) {
                $filters['lesson'] = $lesson;
            }
        }
    
        $premiumLessonsAccess = $this->premiumLessonsAccessRepository->findBy($filters, ['viewedAt' => 'DESC']);
        $allAccesses = $this->premiumLessonsAccessRepository->findAll();
    
        // Liste unique des utilisateurs
        $uniqueUsers = [];
        $uniqueLessons = [];
    
        foreach ($allAccesses as $access) {
            $user = $access->getUser();
            $lesson = $access->getLesson();
    
            if (!array_key_exists($user->getId(), $uniqueUsers)) {
                $uniqueUsers[$user->getId()] = $user;
            }
    
            if (!array_key_exists($lesson->getId(), $uniqueLessons)) {
                $uniqueLessons[$lesson->getId()] = $lesson;
            }
        }
    
        return $this->render('admin/premium_access/index.html.twig', [
            'premiumLessonsAccess' => $premiumLessonsAccess,
            'uniqueUsers' => $uniqueUsers,
            'uniqueLessons' => $uniqueLessons,
            'selectedUser' => $selectedUser,
            'selectedLesson' => $selectedLesson,
        ]);
    }
    
}
