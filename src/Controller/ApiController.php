<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\SurveyAnswer;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\ChatRepository;
use App\Repository\DirectMessageRepository;
use App\Repository\EventRepository;
use App\Repository\SchoolRepository;
use App\Repository\SurveyRepository;
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

    #[Route('/surveys', name: 'survey_index', methods: ['GET'])]
    public function getSurveys(SurveyRepository $repository, string $ref_route = null, array $ref_params = []): Response
    {
        $survey = $repository->findOneByOpen($this->getUser());

        $globalHolidays = [
            ['month' => 1,  'day' => 1,   'name' => 'Neujahr'], // 🎉 Feuerwerk, Uhr auf Mitternacht
            ['month' => 2,  'day' => 14,  'name' => 'Valentinstag'], // ❤️ Herzen, Rosen
            ['month' => 3,  'day' => 8,   'name' => 'Internationaler Frauentag'], // ♀️ Venus-Symbol, Blume
            ['month' => 4,  'day' => 22,  'name' => 'Tag der Erde'], // 🌍 Erde mit Pflanze
            ['month' => 5,  'day' => 1,   'name' => 'Tag der Arbeit'], // 🔧 Hammer, Banner
            ['month' => 6,  'day' => 1,   'name' => 'Weltkindertag'], // 🧸 Ballons, Kindersymbole
            ['month' => 6,  'day' => 5,   'name' => 'Weltumwelttag'], // 🌱 Blatt, Baum
            ['month' => 9,  'day' => 21,  'name' => 'Internationaler Friedenstag'], // 🕊️ Taube, Olivenzweig
            ['month' => 10, 'day' => 31,  'name' => 'Halloween'], // 🎃 Kürbis, Fledermäuse
            ['month' => 12, 'day' => 24,  'name' => 'Heiligabend'], // 🎄 Baum, Lichter
            ['month' => 12, 'day' => 25,  'name' => 'Weihnachten'], // 🎁 Geschenke, Stern
            ['month' => 12, 'day' => 31,  'name' => 'Silvester'], // 🎆 Feuerwerk, Uhr
        ];

        return $this->render('component/_survey.html.twig', [
            'survey' => $survey,
            'ref_route' => $ref_route,
            'ref_params' => $ref_params,
        ]);
    }

    #[Route('/survey/{id}', name: 'survey_submit', methods: ['POST'])]
    public function submitSurveyAnswer(Survey $survey, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->request->get('ref_data'), true);
        $answer = $request->request->get('answer'); // 'y' oder 'n'

        $surveyAnswer = new SurveyAnswer();
        $surveyAnswer->setSurvey($survey);
        $surveyAnswer->setUser($this->getUser());
        if ($answer === 'y') {
            $surveyAnswer->setAnswer(true);
        } elseif ($answer === 'n') {
            $surveyAnswer->setAnswer(false);
        }
        $entityManager->persist($surveyAnswer);
        $entityManager->flush();

        $this->addFlash('success','Vielen Dank fürs Abstimmen.');

        return $this->redirectToRoute($data['route'], $data['params']);
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
    public function getUnreadMessages(Request $request, DirectMessageRepository $chatRepository, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $data['userId'] ?? 2;

        $user = $userRepository->find($userId);

        $initialCount = $chatRepository->countUnreadMessages($user);


        if($initialCount > 0) {
            return new JsonResponse($initialCount);
        }
        return new JsonResponse(false);
    }

}
