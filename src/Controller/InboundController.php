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
        if($request->isMethod('POST')) {
            $inbound = new Inbound();
            $inbound->setSender($request->get('from'));
            $inbound->setSubject($request->get('subject'));
            $inbound->setHtml($request->get('html'));
            $inbound->setText($request->get('text'));
            $entityManager->persist($inbound);
            $entityManager->flush();
            return new Response('OK', Response::HTTP_OK);
        }
        return new Response('Ist auf GET', Response::HTTP_OK);
    }

}
