<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_', methods: ['GET','POST'])]
class AppController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newsletter);
            $entityManager->flush();
            $this->addFlash("success", "Vielen Dank für Deine Anmeldung!");
            return $this->redirectToRoute('app_index');
        }

        return $this->render('app/maintaining.html.twig', [
            'form' => $form,
        ]);
    }
}
