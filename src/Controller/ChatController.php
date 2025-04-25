<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\User;
use App\Form\ChatMessageType;
use App\Repository\ChatRepository;
use App\Repository\DirectMessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile/chat', name: 'chat_')]
class ChatController extends AbstractController
{

    #[Route('', name: 'start')]
    public function chat(UserRepository $userRepository, DirectMessageRepository $messageRepository, EntityManagerInterface $entityManager): Response
    {


        $sendMessages = $messageRepository->findBy(['sender' => $this->getUser()]);
        $receivedMessages = $messageRepository->findBy(['recipient' => $this->getUser()]);

        return $this->render('chat/start.html.twig', [
            'sendMessages' => $sendMessages,
            'receivedMessages' => $receivedMessages,
        ]);
    }

    #[Route('/{id}/write/{subject}', name: 'new')]
    public function new(Request $request, Chat $chat, EntityManagerInterface $entityManager, ?string $subject = null): Response
    {
        $message = new ChatMessage();
        $message->setRead(false);
        $message->setChat($chat);
        $message->setSender($this->getUser());

        if($subject !== null) {
            $message->setSubject("RE: $subject");
        }

        $partner = $chat->getOwner()->getUserIdentifier() === $this->getUser()->getUserIdentifier() ? $chat->getParticipant() : $chat->getOwner();

        $form = $this->createForm(ChatMessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success', 'Deine Nachricht wurde verschickt.');
            return $this->redirectToRoute('chat_start',['slug' => $partner->getSlug()]);
        }

        return $this->render('chat/new.html.twig', [
            'form' => $form,
            'partner' => $partner,
        ]);
    }

    #[Route('/{id}/read', name: 'read')]
    public function read(ChatMessage $message, EntityManagerInterface $entityManager): Response
    {
        $partner = $message->getChat()->getOwner()->getUserIdentifier() === $this->getUser()->getUserIdentifier() ? $message->getChat()->getParticipant() : $message->getChat()->getOwner();

        if(!$message->isRead() and $message->getSender() !== $this->getUser()) {
            $message->setRead(true);
            $entityManager->persist($message);
            $entityManager->flush();
        }

        return $this->render('chat/read.html.twig', [
            'message' => $message,
            'partner' => $partner,
        ]);
    }
}
