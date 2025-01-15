<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/{slug}', name: 'start')]
    public function chat(User $user, UserRepository $userRepository, ChatRepository $chatRepository, EntityManagerInterface $entityManager): Response
    {

        $self = $userRepository->find($this->getUser());

        $owner = $user->getId() < $self->getId() ? $user : $self;
        $participant = $user->getId() < $self->getId() ? $self : $user;

        $chat = $chatRepository->findBy(['owner' => $owner, 'participant' => $participant]);

        if(!$chat) {
            $chat = new Chat();
            $chat->setOwner($owner);
            $chat->setParticipant($participant);
            $entityManager->persist($chat);
            $entityManager->flush();
        }

        $chats = $chatRepository->findAllChats($self);

        return $this->render('chat/start.html.twig', [
            'partner' => $user,
            'chats' => $chats,
            'current_chat' => $chat,
        ]);
    }
}
