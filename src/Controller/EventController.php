<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Repository\BlogPostRepository;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/veranstaltungen', name: 'event_', methods: ['GET','POST'])]
class EventController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(Request $request, EventRepository $repository, EventTypeRepository $typeRepository): Response
    {
        $filteredTypesQuery = trim($request->query->get('types'),',');
        $filteredTypes = array_filter(explode(',', $filteredTypesQuery));
        if (!empty($filteredTypes)) {
            $posts = $repository->findByBlogType($filteredTypes);
        } else {
            $posts = $repository->findAll();
        }

        $types = $typeRepository->findAll();
        return $this->render('blog/event_index.html.twig', [
            'posts' => $posts,
            'types' => $types,
            'filtered_types' => $filteredTypes,
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(Event $post): Response
    {
        return $this->render('blog/event_show.html.twig', [
            'post' => $post,
        ]);
    }
}
