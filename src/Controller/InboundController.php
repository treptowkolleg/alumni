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

        // Ersetze <br> und </p> durch Zeilenumbrüche
        $bodyContent = preg_replace('/<br\s*\/?>/i', "\n", $bodyContent);
        $bodyContent = preg_replace('/<\/p>/i', "\n", $bodyContent);

        // Entferne alle anderen HTML-Tags
        $plainText = strip_tags($bodyContent);

        // HTML-Entities dekodieren
        $plainText = html_entity_decode($plainText, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Mehrfache Leerzeilen auf eine reduzieren
        $plainText = preg_replace("/\n{2,}/", "\n\n", $plainText);

        return trim($plainText);
    }

}
