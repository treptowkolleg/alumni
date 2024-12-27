<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

}
