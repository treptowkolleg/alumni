<?php

namespace App\Controller;

use App\Repository\ChatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/chat', name: 'chat_')]
class ChatController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(ChatRepository $chatRepository): Response
    {
        $chats = $chatRepository->findBy(['owner' => $this->getUser()]);
        return $this->render('chat/index.html.twig', [
            'chats' => $chats,
        ]);
    }
}
