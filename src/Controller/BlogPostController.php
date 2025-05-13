<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use App\Repository\BlogTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/themen', name: 'blog_', methods: ['GET','POST'])]
class BlogPostController extends AbstractController
{

    #[Route('/{page}', name: 'index')]
    public function index(Request $request, BlogPostRepository $repository, BlogTypeRepository $typeRepository, int $page = 1): Response
    {

        $posts = $repository->findPublishedBlogPosts(offset: $page);
        $postCount = count($repository->findPublishedBlogPosts());




        $types = $typeRepository->findByTypes('Blog Erfahrungsbericht Interview Podcast Fachartikel');
        return $this->render('blog/blog_index.html.twig', [
            'posts' => $posts,
            'types' => $types,
            'page' => $page,
            'post_count' => $postCount
        ]);
    }

    #[Route('/details/{slug}', name: 'show')]
    public function show(string $slug, BlogPostRepository $repository): Response
    {
        $post = $repository->findOneBy(['slug' => $slug]);

        // Wenn der BlogPost nicht gefunden wurde, eine Fehlerseite anzeigen
        if (!$post) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
                'message' => 'Die angeforderte Meldung wurde nicht gefunden.',
            ], new Response('', Response::HTTP_NOT_FOUND));
        }


        $prevPost = $repository->findPreviousPost($post->getId());
        $nextPost = $repository->findNextPost($post->getId());

        return $this->render('blog/blog_show.html.twig', [
            'post' => $post,
            'prev_post' => $prevPost,
            'next_post' => $nextPost
        ]);
    }
}
