<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\GlobalSearchType;
use App\Form\NewsletterType;
use App\Repository\BlogPostRepository;
use App\Repository\BlogTypeRepository;
use App\Repository\EventRepository;
use App\Repository\SchoolRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_', methods: ['GET','POST'])]
class AppController extends AbstractController
{

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EventRepository $eventRepository, BlogPostRepository $postRepository): Response
    {
        $events = $eventRepository->findBy([], ['startDate' => 'DESC'],6);
        $posts = $postRepository->findPublishedNews(4);
        $blogPost = $postRepository->findLatestByType('Blog');
        $lead = $postRepository->findLatestLead();
        return $this->render('app/index.html.twig', [
            'events' => $events,
            'posts' => $posts,
            'blog_post' => $blogPost,
            'lead' => $lead
        ]);
    }

    #[Route('/suchergebnisse', name: 'search', methods: ['POST'])]
    public function search(Request $request, BlogPostRepository $postRepository): Response
    {


        $search_form = $this->createForm(GlobalSearchType::class);
        $search_form->handleRequest($request);
        if ($search_form->isSubmitted() && $search_form->isValid()) {
            $results = $postRepository->findByQuery($search_form->get('search')->getData());
            return $this->render('app/search_result.html.twig', [
                'results' => $results,
                'search' => $search_form->get('search')->getData(),
            ]);
        }
        return $this->redirectToRoute('app_index');
    }

    #[Route('/search_form', name: 'search_form', methods: ['GET'])]
    public function searchForm(BlogPostRepository $postRepository): Response
    {
        $search_form = $this->createForm(GlobalSearchType::class);
        return $this->render('component/_search_form.html.twig', [
            'form' => $search_form
        ]);
    }

    #[Route('/about/allgemein', name: 'about_index')]
    public function aboutIndex(BlogPostRepository $postRepository, BlogTypeRepository $blogTypeRepository, UserRepository $userRepository, SchoolRepository $schoolRepository, UserProfileRepository $profileRepository): Response
    {
        $users = count($userRepository->findAll());
        $schools = count($schoolRepository->findAll());
        $profiles = count($profileRepository->findAll());
        $blogType = $blogTypeRepository->findBy(['title' => 'Pressemitteilung']);
        $blogPost = $postRepository->findBy(['type' => $blogType,'isPublished' => true],orderBy: ['updatedAt' => 'DESC'], limit: 6);
        return $this->render('app/about.html.twig',[
            'posts' => $blogPost,
            'users' => $users,
            'schools' => $schools,
            'profiles' => $profiles,
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

    #[Route('/rechtliches/green-data', name: 'green_data')]
    public function greenData(): Response
    {
        return $this->render('app/green_data.html.twig');
    }

}
