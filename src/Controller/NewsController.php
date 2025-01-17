<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/aktuelles', name: 'blog_news_', methods: ['GET','POST'])]
class NewsController extends AbstractController
{

    #[Route('', name: 'all_index')]
    public function index(): Response
    {
        return $this->render('blog/news_overview.html.twig', [
        ]);
    }

    #[Route('/meldungen', name: 'index')]
    public function indexNews(BlogPostRepository $repository): Response
    {
        $posts = $repository->findPublishedNews(25);
        return $this->render('blog/news_index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/meldungen/{slug}', name: 'show')]
    public function show(BlogPost $post): Response
    {

        return $this->render('blog/news_show.html.twig', [
            'post' => $post,
        ]);
    }
}
