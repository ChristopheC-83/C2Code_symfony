<?php

namespace App\Controller;

use App\Entity\UserLesson;
use App\Repository\CommentsLessonsRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use App\Repository\PremiumLessonsAccessRepository;
use App\Repository\UserLessonRepository;
use App\Service\LessonDurationService;
use App\Entity\PremiumLessonsAccess;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoursesController extends AbstractController
{


    private $coursesRepository;
    private $emi;
    private $lessonsRepository;
    private $commentsLessonsRepository;
    private $lessonDurationService;
    private $userLessonRepository;
    private $PremiumLessonAccessRepository;

    public function __construct(
        CoursesRepository $coursesRepository,
        EntityManagerInterface $emi,
        LessonsRepository $lessonsRepository,
        CommentsLessonsRepository $commentsLessonsRepository,
        LessonDurationService $lessonDurationService,
        UserLessonRepository $userLessonRepository,
        PremiumLessonsAccessRepository $PremiumLessonAccessRepository,

    ) {
        $this->coursesRepository = $coursesRepository;
        $this->emi = $emi;
        $this->lessonsRepository = $lessonsRepository;
        $this->commentsLessonsRepository = $commentsLessonsRepository;
        $this->lessonDurationService = $lessonDurationService;
        $this->userLessonRepository = $userLessonRepository;
        $this->PremiumLessonAccessRepository = $PremiumLessonAccessRepository;
    }

    #[Route('/courses', name: 'app_courses')]
    public function index(LessonDurationService $lessonDurationService): Response
    {
        $courses = $this->coursesRepository->findBy(['is_visible' => true]);

        foreach ($courses as $course) {
            $durations = $this->lessonsRepository->getTotalDurationByCourse($course->getId());
            // dd($durations);
            $totalSeconds = $lessonDurationService->sumDurations($durations);
            // dd($totalSeconds);
            $course->totalTime = $lessonDurationService->convertSecondsToTime($totalSeconds);
            // dd($course);
        }
        // dd($courses);

        return $this->render('courses/index.html.twig', [
            'courses' => $courses,
        ]);
    }


    #[Route('/course/{slug}/{position?}', name: 'app_one_course')]
    public function oneCourse(string $slug, ?int $position = null): Response
    {
        $user = $this->getUser();

        $course = $this->coursesRepository->findOneBy(['slug' => $slug]);
        if (!$course || !$course->getIsVisible()) {
            throw $this->createNotFoundException('Cours introuvable.');
        }


        if ($position === null) {
            $currentLesson = $this->lessonsRepository->findFirstLessonByCourse($course);
        } else {
            $currentLesson = $this->lessonsRepository->findOneBy(['course' => $course, 'position' => $position, 'is_visible' => true]);
        }
        if (!$currentLesson) {
            throw $this->createNotFoundException('Leçon introuvable.');
        }

        // lessons pour récupérer les titles
        $lessons = $this->lessonsRepository->findBy(['course' => $course], ['position' => 'ASC']);
        // les commentaires
        $comments = $this->commentsLessonsRepository->findBy(['lesson' => $currentLesson]);

        // 1 - Si utilisateur non connecté et que la leçon est premium

        if (!$user && $currentLesson->getIsPremium()) {

            return $this->render('courses/one-course-free.html.twig', [
                'course' => $course,
                'lessons' => $lessons,
                'lesson' => $currentLesson,
                'comments' => $comments,
            ]);
        }

        // 2 - si utilisateur connecté 

        if ($user && $currentLesson->getIsPremium() && !$this->userLessonRepository->hasPurchasedLesson($user, $currentLesson)) {
            return $this->render('courses/one-course-no-purchased.html.twig', [
                'course' => $course,
                'lessons' => $lessons,
                'lesson' => $currentLesson,
                'comments' => $comments,
            ]);
        }

        // 3 - si user est connecté et a déjà acheté la leçon, on sauvegarde son accés
        if ($user && $currentLesson->getIsPremium() && $this->userLessonRepository->hasPurchasedLesson($user, $currentLesson)) {
            $access = new PremiumLessonsAccess();
            $access->setUser($user);      // Objet User
            $access->setLesson($currentLesson);  // Objet Lesson
            $access->setViewedAt(new \DateTimeImmutable());

            $this->emi->persist($access);
            $this->emi->flush();
        }




        return $this->render('courses/one-course.html.twig', [
            'course' => $course,
            'lessons' => $lessons,
            'lesson' => $currentLesson,
            'comments' => $comments,
        ]);
    }
}
