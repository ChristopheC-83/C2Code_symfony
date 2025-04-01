<?php

namespace App\Controller\Account;

use App\Entity\UserLesson;
use App\Repository\LessonsRepository;
use App\Repository\UserLessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class PurchaseLessonController extends AbstractController
{

    private $emi;
    private $lessonsRepository;

    private $userLessonRepository;

    public function __construct(
        EntityManagerInterface $emi,
        LessonsRepository $lessonsRepository,
        UserLessonRepository $userLessonRepository
    ) {
        $this->emi = $emi;
        $this->lessonsRepository = $lessonsRepository;
        $this->userLessonRepository = $userLessonRepository;
    }


    #[Route('/account/purchase/lesson', name: 'app_account_purchase_lesson')]
    public function index(Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {



        $user = $this->getUser();
        // dd($user);

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour acheter une leçon.');
            return $this->redirectToRoute('app_login');
        }

        // Vérification du token CSRF
        $submittedToken = $request->request->get('_token');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('purchase_lesson', $submittedToken))) {
            throw $this->createAccessDeniedException('Action non autorisée.');
        }

        // Récupération de la leçon
        $lessonId = $request->request->get('lesson_id');
        $lesson = $this->lessonsRepository->find($lessonId);
        $slug = $lesson->getCourse()->getSlug();
        $position = $lesson->getPosition();

        // Vérifier si l'utilisateur a déjà acheté la leçon
        if ($this->userLessonRepository->hasPurchasedLesson($user, $lesson)) {
            // Si l'utilisateur a déjà acheté la leçon, redirige-le ou montre un message
            $this->addFlash('info', 'Vous avez déjà acheté cette leçon.');
            return $this->redirectToRoute('app_one_course', [
                'slug' => $slug,
                'position' => $position
            ]);
        }

        if (!$lesson || !$lesson->getIsPremium()) {
            $this->addFlash('error', 'Leçon introuvable ou non premium.');
            return $this->redirectToRoute('app_courses');
        }

        // Vérification des crédits
        if ($user->getCredits() < $lesson->getPrice()) {
            $this->addFlash('danger', 'Vous n\'avez pas assez de crédits. Merci de recharger votre compte.');

            return $this->redirectToRoute('app_one_course', [
                'slug' => $slug,
                'position' => $position
            ]);
        }

        // Débiter les crédits et enregistrer l'achat
        $user->setCredits($user->getCredits() - $lesson->getPrice());

        $userLesson = new UserLesson();
        $userLesson->setUser($user);
        $userLesson->setLesson($lesson);
        $userLesson->setPurchasedAt(new \DateTimeImmutable());
        $userLesson->setPurchasedPrice($lesson->getPrice());
        $this->emi->persist($userLesson);
        $this->emi->flush();

        $this->addFlash('success', 'Leçon achetée avec succès !');


        // return $this->redirectToRoute('app_one_course', [
        //     'slug' => $lesson->getCourse()->getSlug(),
        //     'position' => $lesson->getPosition()
        // ]);

        // Récupérer la page précédente
        if ($slug && $position)
            return $this->redirectToRoute('app_one_course', [
                'slug' => $slug,
                'position' => $position
            ]);

        // Sinon, rediriger vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }
}
