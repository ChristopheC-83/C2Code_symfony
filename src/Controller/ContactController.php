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
        $formData = $request->request->all();

        // Vérifier si le formulaire a été soumis
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $mail = new Mail();
            $vars = [
                'pseudo' => $formData['_pseudo'],
                'email' => $formData['_email'],
                'message' => $formData['_message'],
            ];

                $mail->send('contact@ducompagnon.fr', 'kiki', 'Contact du site', 'contact.html', $vars);
          
                $this->addFlash('success', 'Message envoyé !');
                return $this->redirectToRoute('app_home');
           
        }

        return $this->render('contact/index.html.twig');
    }
}
