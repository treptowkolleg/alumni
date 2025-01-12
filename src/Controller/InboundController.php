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

    #[Route('/', name: 'receive', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Prüfe den Content-Type, falls nötig
        if (!$request->isMethod('POST')) {
            return new Response('Only POST requests are allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        // Payload auslesen (JSON oder Form-Data)
        $data = json_decode($request->getContent(), true);

        // Prüfen, ob die Daten valide sind
        if (!$data || !isset($data['from']) || !isset($data['to'])) {
            return new Response('Invalid payload', Response::HTTP_BAD_REQUEST);
        }

        // Beispiel: Daten auswerten
        $from = $data['from']; // Absender-E-Mail
        $subject = $data['subject'] ?? '(No Subject)'; // Betreff
        $body = $data['text'] ?? '(No Body)'; // Textinhalt der E-Mail

        // Beispiel: Logging oder Weiterverarbeitung
        $inbound = new Inbound();
        $inbound->setSubject($subject);
        $inbound->setSender($from);
        $inbound->setText($body);
        $entityManager->persist($inbound);
        $entityManager->flush();

        // Erfolgreiche Verarbeitung signalisieren
        return new Response('OK', Response::HTTP_OK);
    }

}
