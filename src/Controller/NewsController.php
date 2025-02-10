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

    #[Route('/meldungen/{page}', name: 'index')]
    public function indexNews(BlogPostRepository $repository, int $page = 1): Response
    {
        $posts = $repository->findPublishedNews(offset: $page);
        $postCount = count($repository->findPublishedNews());
        return $this->render('blog/news_index.html.twig', [
            'page' => $page,
            'posts' => $posts,
            'post_count' => $postCount,
        ]);
    }

    #[Route('/meldungen/details/{slug}', name: 'show')]
    public function show(BlogPost $post, BlogPostRepository $repository): Response
    {
        $types = ['Neuigkeiten', 'Pressemitteilung','Meldung'];

        $prevPost = $repository->findPreviousPost($post->getId(),$types);
        $nextPost = $repository->findNextPost($post->getId(),$types);

        return $this->render('blog/news_show.html.twig', [
            'post' => $post,
            'prev_post' => $prevPost,
            'next_post' => $nextPost
        ]);
    }
}
