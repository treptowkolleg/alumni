<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Repository\BlogPostRepository;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use App\Repository\SchoolRepository;
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
            $filterValues = $request->request->all();
            $request->getSession()->set('filter_event', $filterValues);
        }

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

    #[Route('/details/{slug}', name: 'show')]
    public function show(Event $post): Response
    {
        return $this->render('blog/event_show.html.twig', [
            'post' => $post,
        ]);
    }
}
