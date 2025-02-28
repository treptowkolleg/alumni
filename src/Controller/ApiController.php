<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\ChatRepository;
use App\Repository\EventRepository;
use App\Repository\SchoolRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{

    #[Route('/get-cookie', name: 'cookie_get', methods: ['GET'])]
    public function get(Request $request): Response
    {
        if($request->cookies->has('cookie_consent')) {
            return $this->render('component/cookies.html.twig', []);
        } else {
            return $this->render('component/empty.html.twig', []);
        }
    }

    #[Route('/schools', name: 'schools_all', methods: ['GET'])]
    public function getSchools(SchoolRepository $postRepository): Response
    {
        $counties = [];
        $schools = $postRepository->findAll();
        foreach ($schools as $school) {
            $counties[$school->getCounty()][] = $school;
        }

        ksort($counties);

        return $this->render('component/_footer_schools.html.twig', [
            'items' => $counties
        ]);
    }

    #[Route('/cookie-consent', name: 'cookie_consent', methods: ['POST'])]
    public function consent(Request $request, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Erwarte, dass die Kategorien als Array gesendet werden
        $categories = $data['categories'] ?? [];

        $session->set('cookie_consent', $categories);

        // Serialisiere die Kategorien, um sie im Cookie zu speichern
        $cookieValue = json_encode($categories);

        // Setze ein Cookie mit den Einstellungen
        $response = new JsonResponse(['status' => 'success']);
        $response->headers->setCookie(
            new Cookie(
                'cookie_consent', // Name des Cookies
                $cookieValue,     // Wert des Cookies (z. B. die Kategorien als JSON)
                strtotime('+1 year'), // Ablaufdatum (hier: 1 Jahr gültig)
                '/',             // Pfad
                null,            // Domain (automatisch)
                true,            // Sicherer Cookie (nur HTTPS)
                true,            // HttpOnly (nicht per JavaScript zugreifbar)
                false,           // SameSite-Policy (Standard: lax)
                'lax'
            )
        );

        return $response;
    }

    #[Route('/event/follow/toggle', name: 'event_follow_toggle', methods: ['POST'])]
    public function eventFollowToggle(Request $request, EventRepository $eventRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $data = json_decode($request->getContent(),true);
        $id = $data['id'];

        if ($request->isMethod('post')) {
            $user = $userRepository->find($this->getUser());
            $event = $eventRepository->find($id);

            if(!$user->getFollowedEvents()->contains($event)) {
                $user->addFollowedEvent($event);
                $entityManager->persist($event);
                $entityManager->flush();
                $sampleHasLike = true;
            } else {
                $user->removeFollowedEvent($event);
                $entityManager->persist($event);
                $entityManager->flush();
                $sampleHasLike = false;
            }

            return new JsonResponse([
                'sampleHasLike' => $sampleHasLike
            ]);
        }
        return new JsonResponse('fehler');
    }

    #[Route('/like/toggle', name: 'add_like', methods: ['POST'])]
    public function addLike(Request $request, UserProfileRepository $userProfileRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $data = json_decode($request->getContent(),true);
        $id = $data['id'];

        if ($request->isMethod('post')) {
            $ownUserProfile = $userProfileRepository->findOneBy([
                'user' => $this->getUser()
            ]);
            $friendsUserprofile = $userProfileRepository->find($id);

            if(!$friendsUserprofile->getFriends()->contains($ownUserProfile))
            {
                $friendsUserprofile->addFriend($ownUserProfile);
                $entityManager->persist($friendsUserprofile);
                $entityManager->flush();
                $sampleHasLike = true;
            } else
            {
                $friendsUserprofile->removeFriend($ownUserProfile);
                $entityManager->persist($friendsUserprofile);
                $entityManager->flush();
                $sampleHasLike = false;
            }
            $likes = $friendsUserprofile->getFriends()->count();
            $match = false;
            if(
                $friendsUserprofile->getUserProfiles()->contains($ownUserProfile) and
                $ownUserProfile->getUserProfiles()->contains($friendsUserprofile)
            ) {
                $match = true;
            }
            return new JsonResponse([
                'match' => $match,
                'likes' => $likes,
                'sampleHasLike' => $sampleHasLike
            ]);
        }
        return new JsonResponse('fehler');
    }

    #[Route('/unread-messages', name: 'check_messages', methods: ['POST','GET'])]
    public function getUnreadMessages(Request $request, ChatRepository $chatRepository, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'] ?? 19;

        $user = $userRepository->find($userId);

        $initialCount = $chatRepository->countUnreadMessages($user);

        if($initialCount > 0) {
            return new JsonResponse($initialCount);
        }
        return new JsonResponse(false);
    }

}
