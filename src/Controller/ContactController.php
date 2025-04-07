<?php

namespace App\Controller;

use App\Classe\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        $meta_description = 'Besoin  ou envie de contacter le Compagnon de Code ? Ce formulaire de contact est là pour ça !';

        $formData = $request->request->all();
        // if ($formData){
        // dd($formData);}

        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            // dd($formData);
            $mail = new Mail();
            $vars = [
                'pseudo' => $formData['_pseudo'],
                'email' => $formData['_email'],
                'message' => $formData['_message'],
            ];

            $mail->send('admin@compagnondecode.fr', 'kiki', 'Contact du site', 'contact.html', $vars);

            $this->addFlash('success', 'Message envoyé !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/index.html.twig', [
            'meta_description' => $meta_description,]);
    }
}
