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

        if($html) {
            $body = $this->htmlToPlainText($html);
        } else {
            $body = $text;
        }


        // Beispiel: Logging oder Weiterverarbeitung
        $inbound = new Inbound();
        $inbound->setSubject($subject);
        $inbound->setDepartement($to);
        $inbound->setSender($from);
        $inbound->setText($body);
        $entityManager->persist($inbound);
        $entityManager->flush();

        // Erfolgreiche Verarbeitung signalisieren
        return new Response('OK', Response::HTTP_OK);
    }

    function htmlToPlainText($html): string
    {
        // Body-Inhalt extrahieren
        preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches);
        if (!isset($matches[1])) {
            return ''; // Falls kein Body gefunden wurde, gib einen leeren String zurück
        }
        $bodyContent = $matches[1];
        $bodyContent = mb_convert_encoding($html, 'UTF-8', mb_detect_encoding($bodyContent, 'UTF-8, ISO-8859-1', true));
        $plainText = html_entity_decode($bodyContent, ENT_QUOTES, 'UTF-8');
        // Entferne alle anderen HTML-Tags
        $plainText = strip_tags($plainText);

        // HTML-Entities dekodieren
        $text = preg_replace("/\n{3,}/", "\n\n", $plainText);

        return trim($text);
    }

}
