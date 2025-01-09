<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\User;
use App\Form\BlogPostFormType;
use App\Form\EventFormType;
use App\Form\EventTypeFormType;
use App\Form\UserRoleFormType;
use App\Repository\BlogPostRepository;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/authoring', name: 'authoring_')]
class AuthoringController extends AbstractController
{

    #[Route('/event/types', name: 'event_type_index')]
    public function eventTypeIndex(EventTypeRepository $repository): Response
    {
        $eventTypes = $repository->findAll();
        return $this->render('authoring/event_type_index.html.twig', [
            'event_types' => $eventTypes,
        ]);
    }

    #[Route('/event/types/new', name: 'event_type_new')]
    public function eventTypeNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new EventType();
        $form = $this->createForm(EventTypeFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Event type created.');
            return $this->redirectToRoute('authoring_event_type_index');
        }

        return $this->render('authoring/event_type_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/event/types/{id}/update', name: 'event_type_edit')]
    public function eventTypeUpdate(Request $request, EventType $eventType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventTypeFormType::class, $eventType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eventType);
            $entityManager->flush();
            $this->addFlash('success', 'Event-Typ aktualisiert.');
            return $this->redirectToRoute('authoring_event_type_index');
        }

        return $this->render('authoring/event_type_edit.html.twig', [
            'event_type' => $eventType,
            'form' => $form,
        ]);
    }

    #[Route('/event', name: 'event_index')]
    public function eventIndex(EventRepository $repository): Response
    {
        $events = $repository->findAll();
        return $this->render('authoring/event_index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/new', name: 'event_new')]
    public function eventNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Event created.');
            return $this->redirectToRoute('authoring_event_index');
        }

        return $this->render('authoring/event_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/event/{id}/update', name: 'event_update')]
    public function eventUpdate(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Veranstaltung aktualisiert.');
            return $this->redirectToRoute('authoring_event_index');
        }

        return $this->render('authoring/event_edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/blog', name: 'blogpost_index')]
    public function blogIndex(BlogPostRepository $repository): Response
    {
        $blogPosts = $repository->findAll();
        return $this->render('authoring/blog_index.html.twig', [
            'blog_posts' => $blogPosts,
        ]);
    }

    #[Route('/blog/new', name: 'blogpost_new')]
    public function blogNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostFormType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost = $form->getData();
            $blogPost->setAuthor($this->getUser());
            $entityManager->persist($blogPost);
            $entityManager->flush();
            $this->addFlash('success', 'Post created.');
            return $this->redirectToRoute('authoring_blogpost_index');
        }

        return $this->render('authoring/blog_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/blog/{id}/update', name: 'blogpost_update')]
    public function blogUpdate(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogPostFormType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost->setAuthor($this->getUser());
            $entityManager->persist($blogPost);
            $entityManager->flush();
            $this->addFlash('success', 'Post aktualisiert.');
            return $this->redirectToRoute('authoring_blogpost_index');
        }

        return $this->render('authoring/blog_edit.html.twig', [
            'post' => $blogPost,
            'form' => $form,
        ]);
    }


}
