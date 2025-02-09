<?php

namespace App\Controller;

use App\Entity\Inbound;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/inbound', name: 'inbound_', methods: ['GET','POST'])]
class InboundController extends AbstractController
{

    #[Route('', name: 'receive', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {

        if (!$request->isMethod('POST')) {
            return new Response('Only POST requests are allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        // Beispiel: Daten auswerten
        $from = $request->request->get('from'); // Absender-E-Mail
        $to = $request->request->get('to'); // Absender-E-Mail
        $subject = $request->request->get('subject'); // Betreff
        $text = $request->request->get('text'); // Textinhalt der E-Mail
        $html = $request->request->get('html'); // Textinhalt der E-Mail

        // Beispiel: Logging oder Weiterverarbeitung
        $inbound = new Inbound();
        $inbound->setSubject($subject);
        $inbound->setDepartement($to);
        $inbound->setSender($from);
        $inbound->setText($text);
        $inbound->setHtml($html);
        $entityManager->persist($inbound);
        $entityManager->flush();

        // Erfolgreiche Verarbeitung signalisieren
        return new Response('OK', Response::HTTP_OK);
    }

}
