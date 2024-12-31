<?php

namespace App\Controller;

use App\Repository\CommentsLessonsRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
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

    public function __construct(
        CoursesRepository $coursesRepository,
        EntityManagerInterface $emi,
        LessonsRepository $lessonsRepository,
        CommentsLessonsRepository $commentsLessonsRepository,

    ) {
        $this->coursesRepository = $coursesRepository;
        $this->emi = $emi;
        $this->lessonsRepository = $lessonsRepository;
        $this->commentsLessonsRepository = $commentsLessonsRepository;
    }

    #[Route('/courses', name: 'app_courses')]
    public function index(): Response
    {
        $courses = $this->coursesRepository->findBy(['is_visible' => true]);

        return $this->render('courses/index.html.twig', [
            'courses' => $courses,
        ]);
    }


    #[Route('/course/{slug}/{position?}', name: 'app_one_course')]
    public function oneCourse(string $slug, ?int $position = null): Response
    {
        $course = $this->coursesRepository->findOneBy(['slug' => $slug]);
        if (!$course || !$course->getIsVisible()) {
            throw $this->createNotFoundException('Cours introuvable.');
        }

        if ($position === null) {
            $currentLesson = $this->lessonsRepository->findFirstLessonByCourse($course);
        } else {
            $currentLesson = $this->lessonsRepository->findOneBy(['course' => $course, 'position' => $position, 'is_visible' => true]);
        }

        $comments = $this->commentsLessonsRepository->findBy(['lesson' => $currentLesson]);

        if (!$currentLesson) {
            throw $this->createNotFoundException('Leçon introuvable.');
        }

        $lessons = $this->lessonsRepository->findBy(['course' => $course], ['position' => 'ASC']);

        return $this->render('courses/one-course.html.twig', [
            'course' => $course,
            'lessons' => $lessons,
            'lesson' => $currentLesson,
            'comments' => $comments,
        ]);
    }
}
