<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\CommentsLessons;
use App\Entity\Lessons;
use App\Form\LessonsType;
use App\Repository\CommentsLessonsRepository;
use App\Repository\CoursesRepository;
use App\Repository\LessonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LessonsController extends AbstractController
{
    private $lessonsRepository;
    private $coursesRepository;
    private $emi;
    private $commentsLessonsRepository;

    public function __construct(
        LessonsRepository $lessonsRepository,
        CoursesRepository $coursesRepository,
        EntityManagerInterface $emi,
        CommentsLessonsRepository $commentsLessonsRepository
    ) {
        $this->lessonsRepository = $lessonsRepository;
        $this->coursesRepository = $coursesRepository;
        $this->emi = $emi;
        $this->commentsLessonsRepository = $commentsLessonsRepository;
    }

    // voir les leçons d'un cours
    #[Route('/lessons/show/{slug}', name: 'app_lessons_show', methods: ['GET'])]
    public function index($slug): Response
    {
        $course = $this->coursesRepository->findOneBy(['slug' => $slug]);
        $idCourse = $course->getId();

        $lessons = $this->lessonsRepository->findBy(['course' => $idCourse]);



        return $this->render('lessons/index.html.twig', [
            'lessons' => $lessons,
            'slug' => $slug,
            'course' => $course,
            'idCourse' => $idCourse,
        ]);
    }

    // créer une leçon pour un cours
    #[Route('/lessons/create-in/{slug}', name: 'app_lessons_new', methods: ['GET', 'POST'])]
    public function new($slug, Request $request): Response
    {

        $course = $this->coursesRepository->findOneBy(['slug' => $slug]);

        $lesson = new Lessons();
        $form = $this->createForm(LessonsType::class, $lesson, ['current_course' => $course]);
        $form->handleRequest($request);
        $lesson->setCourse($course);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($lesson->getCourse()->getSlug());
            $lesson->setCreatedAt(new \DateTimeImmutable());

            if ($lesson->getPosition() == null) {
                $lesson->setPosition(1);
            }

            $existingLesson = $this->lessonsRepository->findOneBy([
                'course' => $course,
                'position' => $lesson->getPosition(),
            ]);

            while ($existingLesson) {
                $lesson->setPosition($lesson->getPosition() + 1);

                // Vérifie si une autre leçon a maintenant la même position
                $existingLesson = $this->lessonsRepository->findOneBy([
                    'course' => $course,
                    'position' => $lesson->getPosition(),
                ]);
            }

            $this->emi->persist($lesson);
            $this->emi->flush();

            $this->updateNbLessonsAllCourses();
            $this->addFlash('success', 'Leçon créée avec succès');

            return $this->redirectToRoute('app_lessons_show', [
                'slug' => $lesson->getCourse()->getSlug(),

            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lessons/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
            'slug' => $slug,
            'course' => $course,
        ]);
    }



    #[Route('/lessons/update/{id}', name: 'app_lessons_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lessons $lesson,): Response
    {
        $course = $lesson->getCourse();

        $form = $this->createForm(LessonsType::class, $lesson, ['current_course' => $course]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lesson->setUpdatedAt(new \DateTimeImmutable());

            if ($lesson->getPosition() == null) {
                $lesson->setPosition(1);
            }

            $existingLesson = $this->lessonsRepository->findOneBy([
                'course' => $course,
                'position' => $lesson->getPosition(),
            ]);
            
            if ($existingLesson != $lesson) {

                while ($existingLesson) {
                    $lesson->setPosition($lesson->getPosition() + 1);

                    // Vérifie si une autre leçon a maintenant la même position
                    $existingLesson = $this->lessonsRepository->findOneBy([
                        'course' => $course,
                        'position' => $lesson->getPosition(),
                    ]);
                }
            }
            // if ($lesson->getIsPremium() == false) {
            //     dd("non");
            // } else if ($lesson->getIsPremium() == true) {
            //     dd("oui");
            // } else {
            //     dd("rien");
            // }
            $this->emi->flush();

            $this->updateNbLessonsAllCourses();
            return $this->redirectToRoute('app_lessons_show', ['slug' => $lesson->getCourse()->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('lessons/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }


    #[Route('/lesson/updateVisibility/{id}', name: 'app_lesson_update_visibility', methods: ['GET'])]
    public function updateVisibility(int $id, Request $request): Response
    {

        $user = $this->getUser();

        if (!$user || $user->getRoles()[0] != 'ROLE_ADMIN') {
            $this->addFlash('danger', 'On s\'est perdu ?');
            return $this->redirectToRoute('app_home');
        }

        $lesson = $this->emi->getRepository(Lessons::class)->find($id);
        if (!$lesson) {
            throw $this->createNotFoundException('La leçon demandée n\'existe pas.');
        }

        // Inverse la valeur de is_visible
        $lesson->setIsVisible(!$lesson->getIsVisible());

        // Persist et flush
        $this->emi->persist($lesson);
        $this->emi->flush();

        $this->updateNbLessonsAllCourses();
        $this->addFlash('success', 'La visibilité de la leçon a été mise à jour.');

        // Reste sur la page actuelle
        return $this->redirect($request->headers->get('referer'));
    }
    #[Route('/lesson/updatePremium/{id}', name: 'app_lesson_update_premium', methods: ['GET'])]
    public function updatePremium(int $id, Request $request): Response
    {

        $user = $this->getUser();

        if (!$user || $user->getRoles()[0] != 'ROLE_ADMIN') {
            $this->addFlash('danger', 'On s\'est perdu ?');
            return $this->redirectToRoute('app_home');
        }

        $lesson = $this->emi->getRepository(Lessons::class)->find($id);
        if (!$lesson) {
            throw $this->createNotFoundException('La leçon demandée n\'existe pas.');
        }

        // Inverse la valeur de is_visible
        $lesson->setIsPremium(!$lesson->getIsPremium());

        // Persist et flush
        $this->emi->persist($lesson);
        $this->emi->flush();

        $this->addFlash('success', 'Le Premium de la leçon a été mise à jour.');

        // Reste sur la page actuelle
        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/lesson-comment', name: 'post_lesson_comment', methods: ['POST'])]
    public function addComment(Request $request)
    {
        $lessonId = $request->request->get('lesson_id');
        $lesson = $this->lessonsRepository->find($lessonId);

        if (!$lesson) {
            throw $this->createNotFoundException('La leçon demandée n\'existe pas.');
        }

        $comment = new CommentsLessons();

        // Si l'utilisateur est connecté, utilise son ID et pseudo
        if ($this->getUser()) {
            // Vérifie si le pseudo de l'utilisateur est défini
            $user = $this->getUser();
            if ($user && $user->getPseudo()) {
                $comment->setAuthor($user->getPseudo());
                $comment->setUser($user); // Assure-toi que setUser attend l'objet User
            } else {
                throw $this->createNotFoundException('L\'utilisateur n\'a pas de pseudo défini');
            }
        } else {
            // Sinon, récupère le pseudo du formulaire
            $pseudo = $request->request->get('pseudo');
            $comment->setAuthor($pseudo);
        }

        // Test si author est vide
        if (empty($comment->getAuthor())) {
            throw $this->createNotFoundException('Veuillez saisir un pseudo');
        }
        //  test si text vide
        if (empty($request->request->get('comment_text'))) {
            throw $this->createNotFoundException('Veuillez saisir un commentaire');
        }

        // Récupérer le texte du commentaire
        $commentText = $request->request->get('comment_text');
        $comment->setComment($commentText);

        // Lier le commentaire à l'article
        $comment->setLesson($lesson);

        $this->emi->persist($comment);
        $this->emi->flush();

        // envoyer un mail à l'admin pour prévenir d'un nouveau commantaire
        $mail = new Mail();
        $mail->send('contact@ducompagnon.fr', 'Admin', 'Nouveau commentaire dans une leçon', 'new_comment.html', [
            'author' => $comment->getAuthor(),
            'comment' => $comment->getComment(),
            'article' => $lesson->getTitle()
        ]);

        $this->addFlash('success', 'Commentaire ajouté avec succès');

        return $this->redirectToRoute('app_one_course', ['slug' => $lesson->getCourse()->getSlug(), 'position' => $lesson->getPosition()], Response::HTTP_SEE_OTHER);
    }


    #[Route('/comment-lesson/{id}/delete', name: 'delete_comment_lesson', methods: ['POST'])]
    public function deleteComment($id, Request $request)
    {

        $user = $this->getUser();
        // user est admin
        if (!$user || $user->getRoles()[0] != 'ROLE_ADMIN') {
            throw $this->createAccessDeniedException('Vous n\'avez pas les permissions nécessaires.');
        }

        $comment = $this->commentsLessonsRepository->find($id);
        if (!$comment) {
            throw $this->createNotFoundException('Commentaire introuvable.');
        }

        $lesson = $comment->getLesson();

        $this->emi->remove($comment);
        $this->emi->flush();
        $this->addFlash('success', 'Commentaire supprimé avec succès');


        return $this->redirectToRoute('app_one_course', ['slug' => $lesson->getCourse()->getSlug(), 'position' => $lesson->getPosition()], Response::HTTP_SEE_OTHER);
    }



    #[Route('/lessons/delete/{id}', name: 'app_lessons_delete', methods: ['POST'])]
    public function delete(Request $request, Lessons $lesson): Response
    {
        if ($this->isCsrfTokenValid('delete' . $lesson->getId(), $request->getPayload()->getString('_token'))) {

            $this->emi->remove($lesson);
            $this->emi->flush();
            $this->updateNbLessonsAllCourses();
        }

        return $this->redirectToRoute('app_lessons_show', ['slug' => $lesson->getCourse()->getSlug()], Response::HTTP_SEE_OTHER);
    }

    private function updateNbLessonsAllCourses()
    {
        $courses = $this->coursesRepository->findAll();
        foreach ($courses as $course) {
            $course->setNbLessons($this->lessonsRepository->countLessonsByCourseId($course->getId()));
            $this->emi->persist($course);

            $this->emi->flush();
        }
    }
}
