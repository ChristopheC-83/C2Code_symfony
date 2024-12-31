<?php

namespace App\Controller\Creator;

use App\Entity\Courses;
use App\Form\CoursesType;
use App\Repository\CoursesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CoursesController extends AbstractController
{

    private $coursesRepository;
    private $emi;

    public function __construct(
        CoursesRepository $coursesRepository,
        EntityManagerInterface $emi,
    ) {
        $this->coursesRepository = $coursesRepository;
        $this->emi = $emi;
    }

    #[Route('/creator/courses/management', name: 'app_creator_courses_management')]
    public function coursesManager(): Response
    {

        $user = $this->getUser();
        $courses = $this->coursesRepository->findAll();

        if (!$user || $user->getRoles()[0] != 'ROLE_ADMIN') {
            $this->addFlash('danger', 'On s\'est perdu ?');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('creator/courses/manage.html.twig', [
            'courses' => $courses
        ]);
    }

    #[Route('/creator/courses/create', name: 'app_creator_courses_create', methods: ['GET', 'POST'])]
public function createCourse(Request $request, SluggerInterface $slugger): Response
{
    $course = new Courses();
    $form = $this->createForm(CoursesType::class, $course);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Générer un slug basé sur le titre
        $slug = $slugger->slug($course->getTitle())->lower();

        // Vérifier si le slug existe
        $existingCourse = $this->emi->getRepository(Courses::class)->findOneBy(['slug' => $slug]);

        if ($existingCourse) {
            // Enregistrer temporairement le cours sans slug pour obtenir un ID
            $course->setSlug("");
            $this->emi->persist($course);
            $this->emi->flush();

            // Rediriger l'utilisateur vers la page de modification
            $this->addFlash('danger', 'Le slug existe déjà. Veuillez le modifier.');
            return $this->redirectToRoute('app_creator_courses_update', ['id' => $course->getId()]);
        }

        // Enregistrer avec le slug généré
        $course->setSlug($slug);
        $this->emi->persist($course);
        $this->emi->flush();

        $this->addFlash('success', 'Le cours "' . $course->getTitle() . '" a bien été ajouté.');
        return $this->redirectToRoute('app_creator_courses_management', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('creator/courses/create.html.twig', [
        'course' => $course,
        'form' => $form->createView()
    ]);
}


    #[Route('/creator/courses/updateVisibility/{id}', name: 'app_creator_courses_update_visibility', methods: ['GET'])]
    public function updateVisibility(int $id, Request $request): Response
    {

        $user = $this->getUser();

        if (!$user || $user->getRoles()[0] != 'ROLE_ADMIN') {
            $this->addFlash('danger', 'On s\'est perdu ?');
            return $this->redirectToRoute('app_home');
        }

        $course = $this->emi->getRepository(Courses::class)->find($id);
        if (!$course) {
            throw $this->createNotFoundException('Le cours demandé n\'existe pas.');
        }

        // Inverse la valeur de is_visible
        $course->setIsVisible(!$course->getIsVisible());

        // Persist et flush
        $this->emi->persist($course);
        $this->emi->flush();
        $this->addFlash('success', 'La visibilité du cours a été mise à jour.');

        // Reste sur la page actuelle
        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/creator/courses/update/{id}', name: 'app_creator_courses_update', methods: ['GET', 'POST'])]
    public function updateCourse($id, Request $request): Response
    {

        $user = $this->getUser();

        if (!$user || $user->getRoles()[0] != 'ROLE_ADMIN') {
            $this->addFlash('danger', 'On s\'est perdu ?');
            return $this->redirectToRoute('app_home');
        }

        $course = $this->emi->getRepository(Courses::class)->find($id);
        if (!$course) {
            throw $this->createNotFoundException('Le cours demandé n\'existe pas.');
        }


        // Créer le formulaire pour la mise à jour du cours
        $form = $this->createForm(CoursesType::class, $course);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);




        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData()->getSlug());

            // vérifier si le slug existe déjà
            $oldCourse = $this->emi->getRepository(Courses::class)->findOneBy(['slug' => $form->getData()->getSlug()]);
            if ($oldCourse && $oldCourse->getId() != $course->getId()) {
                $this->addFlash('danger', 'Le slug existe déjà.');
                return $this->redirectToRoute('app_creator_courses_update', ['id' => $id]);
            }


            // Enregistrer les modifications dans la base de données
            $this->emi->persist($course);
            $this->emi->flush();

            // Ajouter un message flash pour informer l'utilisateur du succès
            $this->addFlash('success', 'Le cours a été mis à jour avec succès.');

            // Rediriger vers la page de gestion des cours ou vers le détail du cours
            return $this->redirectToRoute('app_creator_courses_management');
        }

        // Rendre la vue avec le formulaire
        return $this->render('creator/courses/update.html.twig', [
            'form' => $form->createView(),
            'course' => $course,
        ]);
    }
    #[Route('/creator/courses/delete/{id}', name: 'app_creator_courses_delete', methods: ['GET'])]
    public function deleteCourse($id, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user || $user->getRoles()[0] != 'ROLE_ADMIN') {
            $this->addFlash('danger', 'On s\'est perdu ?');
            return $this->redirectToRoute('app_home');
        }

        $course = $this->emi->getRepository(Courses::class)->find($id);
        if (!$course) {
            throw $this->createNotFoundException('Le cours demandé n\'existe pas.');
        }


        // Persist et flush
        $this->emi->remove($course);
        $this->emi->flush();
        $this->addFlash('success', 'Cours supprimé avec succés.');




        // Reste sur la page actuelle
        return $this->redirect($request->headers->get('referer'));
    }
}
