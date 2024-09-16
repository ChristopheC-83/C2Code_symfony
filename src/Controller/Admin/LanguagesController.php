<?php

namespace App\Controller\Admin;

use App\Entity\Languages;
use App\Form\LanguagesType;
use App\Repository\LanguagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/langages')]
#[IsGranted('ROLE_ADMIN')]
final class LanguagesController extends AbstractController
{
    #[Route(name: 'app_admin_languages_index', methods: ['GET'])]
    public function index(LanguagesRepository $languagesRepository): Response
    {
        return $this->render('admin/languages/index.html.twig', [
            'languages' => $languagesRepository->findAll(),
        ]);
    }

    #[Route('/nouveau_langage', name: 'app_admin_languages_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $language = new Languages();
        $form = $this->createForm(LanguagesType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            $entityManager->persist($language);
            $entityManager->flush();
            $this->addFlash(
               'success',
               $language->getLanguage().' a bien été ajouté'
            );

            return $this->redirectToRoute('app_admin_languages_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/languages/new.html.twig', [
            'language' => $language,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_languages_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Languages $language, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LanguagesType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_languages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/languages/edit.html.twig', [
            'language' => $language,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_languages_delete', methods: ['POST'])]
    public function delete(Request $request, Languages $language, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$language->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($language);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_languages_index', [], Response::HTTP_SEE_OTHER);
    }
}
