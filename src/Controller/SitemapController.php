<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class SitemapController extends AbstractController
{

    #[Route('/sitemap.xml', name: 'sitemap', defaults: ["_format" => "xml"])]
    public function index(RouterInterface $router): Response
    {
        $routes = $router->getRouteCollection();
        $urls = [];

        foreach ($routes as $routeName => $route) {
            // Skip routes with parameters and admin routes

            $compiledRoute = $route->compile();
            $variables = $compiledRoute->getVariables();

            // Skip if the route has required (non-optional) parameters
            if (!empty($variables) && array_diff($variables, $route->getDefaults())) {
                continue;
            }

            if (
                str_contains($routeName, 'admin') ||
                str_contains($routeName, 'profile') ||
                str_contains($routeName, 'api') ||
                str_contains($routeName, 'register') ||
                str_contains($routeName, 'verify') ||
                str_contains($routeName, 'confirm') ||
                str_contains($routeName, 'sitemap') ||
                str_contains($routeName, 'chat') ||
                str_contains($routeName, 'authoring') ||
                str_contains($routeName, 'search') ||
                str_contains($routeName, 'inbound')
            ) {
                continue;
            }

            $urls[] = [
                'loc' => $this->generateUrl($routeName, [], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ];
        }

        $urls[] = [
            'loc' => $this->generateUrl('blog_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => $this->generateUrl('event_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => $this->generateUrl('blog_news_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => $this->generateUrl('people_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        $response = new Response($this->renderView('sitemap/sitemap.xml.twig', [
            'urls' => $urls,
        ]));

        $response->headers->set('Content-Type', 'application/xml');

        return $response;
    }

}
