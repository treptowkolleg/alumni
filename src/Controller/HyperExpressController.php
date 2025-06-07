<?php

namespace App\Controller;

use App\Entity\Gruschel;
use App\Entity\Survey;
use App\Entity\SurveyAnswer;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\ChatRepository;
use App\Repository\DirectMessageRepository;
use App\Repository\EventRepository;
use App\Repository\GruschelRepository;
use App\Repository\SchoolRepository;
use App\Repository\SurveyRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/htmx', name: 'htmx_')]
class HyperExpressController extends AbstractController
{

    #[Route('/get', name: 'get', methods: ['POST'])]
    public function get(Security $security, UserProfileRepository $profileRepository): Response
    {

        if(!$security->getUser()) return new Response(null, Response::HTTP_FORBIDDEN);

        $profiles = $profileRepository->findBy(['networkState' => 'public']);

        return $this->render('component/_people_list_short.html.twig', [
            'people' => $profiles,
        ]);
    }

}
