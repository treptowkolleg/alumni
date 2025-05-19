<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\DirectMessage;
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
    public function chat(DirectMessageRepository $messageRepository): Response
    {
        $sendMessages = $messageRepository->findBy(['sender' => $this->getUser()]);
        $receivedMessages = $messageRepository->findBy(['recipient' => $this->getUser()]);

        return $this->render('chat/start.html.twig', [
            'sendMessages' => $sendMessages,
            'receivedMessages' => $receivedMessages,
        ]);
    }

    #[Route('/write/{subject}', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager, ?string $subject = null): Response
    {
        $message = new DirectMessage();
        $message->setIsRead(false);
        $message->setSender($this->getUser());
        $message->setIsDeleted(false);
        $message->setIsPinned(false);

        $form = $this->createForm(ChatMessageType::class, $message, [
            'me' => $this->getUser(),
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success', 'Deine Nachricht wurde verschickt.');
            return $this->redirectToRoute('chat_start');
        }

        return $this->render('chat/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/read', name: 'read')]
    public function read(DirectMessage $message, EntityManagerInterface $entityManager): Response
    {

        if(!$message->isRead() and $message->getSender() !== $this->getUser()) {
            $message->setIsRead(true);
            $entityManager->persist($message);
            $entityManager->flush();
        }

        return $this->render('chat/read.html.twig', [
            'message' => $message,
        ]);
    }
}
