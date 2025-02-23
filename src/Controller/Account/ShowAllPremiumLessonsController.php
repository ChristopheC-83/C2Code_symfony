<?php

namespace App\Controller\Account;

use App\Repository\LessonsRepository;
use App\Repository\UserLessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShowAllPremiumLessonsController extends AbstractController
{
    private $lessonsRepository;
    private $userLessonRepository;

    public function __construct(LessonsRepository $lessonsRepository, UserLessonRepository $userLessonRepository)
    {
        $this->lessonsRepository = $lessonsRepository;
        $this->userLessonRepository = $userLessonRepository;
    }
    
    #[Route('/voir-les-premiums', name: 'app_account_show_all_premium_lessons')]
    public function index(): Response
    {
        $user = $this->getUser();
        $premiumLessons = $this->lessonsRepository->findBy(['is_premium' => 1]);


        // Trier les leçons en deux tableaux
        $lessonsNotPurchased = [];
        $lessonsPurchased = [];

        foreach ($premiumLessons as $lesson) {
            if ($this->userLessonRepository->hasPurchasedLesson($user, $lesson)){
                $lessonsPurchased[] = $lesson;
            } else {
                $lessonsNotPurchased[] = $lesson;
            }
        }

        return $this->render('account/show_all_premium_lessons/index.html.twig', [
            'lessonsNotPurchased' => $lessonsNotPurchased,
            'lessonsPurchased' => $lessonsPurchased,
        ]);
    }
}
