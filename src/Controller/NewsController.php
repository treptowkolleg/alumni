<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\PersonOffer;
use App\Repository\BlogPostRepository;
use App\Repository\PersonOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/stellenangebote/{page}', name: 'offer_index')]
    public function offerIndex(Request $request, PersonOfferRepository $offerRepository, int $page = 1): Response
    {
        $offers = $offerRepository->findBy(['active' => true]);
        $counties = [];
        $cities = [];
        $types = [];

        foreach ($offers as $offer) {
            $counties[$offer->getCounty()] = $offer->getCounty();
            $cities[$offer->getCity()] = $offer->getCity();
            $types[$offer->getType()->getTitle()] = $offer->getType()->getTitle();
        }


        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
            'counties' => $counties,
            'cities' => $cities,
            'types' => $types,
            'page' => $page,
        ]);
    }

    #[Route('/stellenangebote/{id}/show', name: 'offer_show')]
    public function offerShow(PersonOffer $offer): Response
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
        ]);
    }

}
