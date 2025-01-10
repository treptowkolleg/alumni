<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\BlogPostRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_', methods: ['GET','POST'])]
class AppController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(EventRepository $eventRepository, BlogPostRepository $postRepository): Response
    {
        $events = $eventRepository->findBy([], ['startDate' => 'DESC'],6);
        $posts = $postRepository->findPublishedNews(4);
        $podcast = $postRepository->findLatestByType('Podcast');
        $interview = $postRepository->findLatestByType('Interview');
        $blogPost = $postRepository->findLatestByType('Blog');
        $lead = $postRepository->findLatestLead();
        return $this->render('app/index.html.twig', [
            'events' => $events,
            'posts' => $posts,
            'podcast' => $podcast,
            'interview' => $interview,
            'blog_post' => $blogPost,
            'lead' => $lead
        ]);
    }

    #[Route('/rechtliches/datenschutz', name: 'privacy')]
    public function privacy(): Response
    {
        return $this->render('app/privacy.html.twig');
    }

    #[Route('/rechtliches/impressum', name: 'imprint')]
    public function imprint(): Response
    {
        return $this->render('app/imprint.html.twig');
    }

    #[Route('/rechtliches/nutzungsbedingungen', name: 'terms')]
    public function terms(): Response
    {
        return $this->render('app/terms.html.twig');
    }

}
