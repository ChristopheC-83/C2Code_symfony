<?php

namespace App\Controller\Admin;

use App\Entity\Types;
use App\Form\TypesType;
use App\Repository\TypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/types')]
#[IsGranted('ROLE_ADMIN')]
final class TypesController extends AbstractController
{
    #[Route(name: 'app_admin_types_index', methods: ['GET'])]
    public function index(TypesRepository $typesRepository): Response
    {
        return $this->render('admin/types/index.html.twig', [
            'types' => $typesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_types_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = new Types();
        $form = $this->createForm(TypesType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_types_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/types/new.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_types_show', methods: ['GET'])]
    public function show(Types $type): Response
    {
        return $this->render('admin/types/show.html.twig', [
            'type' => $type,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_types_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Types $type, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypesType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_types_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/types/edit.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_types_delete', methods: ['POST'])]
    public function delete(Request $request, Types $type, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($type);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_types_index', [], Response::HTTP_SEE_OTHER);
    }
}
