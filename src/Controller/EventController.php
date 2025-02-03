<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Repository\BlogPostRepository;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use App\Repository\SchoolRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/veranstaltungen', name: 'event_', methods: ['GET','POST'])]
class EventController extends AbstractController
{

    #[Route('/{page}', name: 'index')]
    public function index(Request $request, SchoolRepository $schoolRepository, EventRepository $repository, EventTypeRepository $typeRepository, int $page = 1): Response
    {
        if($request->isMethod('POST')) {
            $request->getSession()->set('filter_event', $request->request->all());
        }

        $filterValues = $request->getSession()->get('filter_event');

        $posts = $repository->findBySearchQuery($filterValues ?? null, offset: $page);
        $eventCount = count($repository->findBySearchQuery($filterValues ?? null));


        $types = $typeRepository->findAll();
        return $this->render('blog/event_index.html.twig', [
            'filterValues' => $request->getSession()->get('filter_event'),
            'schools' => $schoolRepository->findAll(),
            'posts' => $posts,
            'event_count' => $eventCount,
            'types' => $types,
            'page' => $page,
        ]);
    }

    #[Route('/details/{slug}', name: 'show', methods: ['GET','POST'])]
    public function show(Request $request, Event $post, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        if($request->isMethod('POST')) {
            $user = $userRepository->find($this->getUser());
            $event = $post;

            if(!$user->getFollowedEvents()->contains($event)) {
                $user->addFollowedEvent($event);
            } else {
                $user->removeFollowedEvent($event);
            }
            $entityManager->persist($event);
            $entityManager->flush();
        }

        return $this->render('blog/event_show.html.twig', [
            'post' => $post,
        ]);
    }
}
