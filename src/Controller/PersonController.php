<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Entity\Gruschel;
use App\Entity\PinboardEntry;
use App\Entity\School;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Enums\PerformanceCourseEnum;
use App\Operator\SoundExpression;
use App\Repository\BlogPostRepository;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use App\Repository\HobbyCategoryRepository;
use App\Repository\PersonOfferRepository;
use App\Repository\PinboardEntryRepository;
use App\Repository\SchoolRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/personen', name: 'people_', methods: ['GET','POST'])]
class PersonController extends AbstractController
{

    #[Route('/{filter}/{page}', name: 'index')]
    public function index(Request $request, UserRepository $userRepository, UserProfileRepository $repository, SchoolRepository $schoolRepository, string $filter = 'all', int $page = 1): Response
    {
        if($request->isMethod('POST')) {
            $filterValues = $request->request->all();
            $request->getSession()->set('filter_people', $filterValues);
        }

        $peopleCount = 0;
        $search = $request->getSession()->get('filter_people')['person'] ?? null;
        $schools = $request->getSession()->get('filter_people')['school'] ?? null;
        $courses = $request->getSession()->get('filter_people')['course'] ?? null;
        $startDate = $request->getSession()->get('filter_people')['start_date'] ?? null;
        $endDate = $request->getSession()->get('filter_people')['end_date'] ?? null;

        $searchArray = explode(' ', $search);
        if(count($searchArray) > 1){
            $name = array_pop($searchArray);
            $firstname = implode(' ', $searchArray);
        } else {
            $firstname = false;
            $name = array_pop($searchArray);
        }
        $people = null;

        $usedDates = $repository->findExamDates();

        $names = [
            "Anna Müller",
            "Carlos Rodríguez",
            "Fatima Al-Mansouri",
            "Yuki Tanaka",
            "Liam O'Connor",
            "Sophia Rossi",
            "Ivan Petrov",
            "Chen Wei",
            "Aisha Ndiaye",
            "Jakub Kowalski",
            "Emily Johnson",
            "Hassan Ahmed",
            "Leila Dupont",
            "Mateo Fernández",
            "Zhang Xiaoming",
            "Katarina Novak",
            "Raj Patel",
            "Mehmet Yılmaz",
            "Olga Ivanova",
            "Gustav Svensson"
        ];

        if($filter == 'all') {
            $people = $repository->findBySearchQuery($name, $firstname, $schools, $courses, $startDate, $endDate, offset: $page);
            $peopleCount = count($repository->findBySearchQuery($name, $firstname, $schools, $courses, $startDate, $endDate));
        }


        if($filter == 'school') {
            if($this->isGranted('ROLE_USER')) {
                $user = $userRepository->find($this->getUser());
                if($school = $user->getSchool()) {
                    $filtered = $request->getSession()->get('filter_people');
                    $filtered['school'] = [$school->getId()];
                    $request->getSession()->set('filter_people',$filtered);
                    $people = $repository->findBySearchQuery($name, $firstname, [$school->getId()], $courses, $startDate, $endDate, offset: $page);
                    $peopleCount = count($repository->findBySearchQuery($name, $firstname, [$school->getId()], $courses, $startDate, $endDate));
                } else {
                    $this->addFlash('warning','Du hast dich keiner Bildungseinrichtung zugeordnet!');
                    return $this->redirectToRoute('people_index');
                }
            } else {
                return $this->redirectToRoute('people_index');
            }
        }

        if($filter == 'freunde') {
            if($this->isGranted('ROLE_USER')) {
                $user = $userRepository->find($this->getUser());
                if ($result = $user->getUserProfiles()->first()) {
                    $people = $repository->findBySearchQuery($name, $firstname, $schools, $courses, $startDate, $endDate, $result, offset: $page);
                    $peopleCount = count($repository->findBySearchQuery($name, $firstname, $schools, $courses, $startDate, $endDate, $result));
                }
                else {
                    $people = [];
                }
            } else {
                return $this->redirectToRoute('people_index');
            }
        }

        return $this->render('people/index.html.twig', [
            'filterValues' => $request->getSession()->get('filter_people'),
            'filter' => $filter,
            'page' => $page,
            'people' => $people,
            'people_count' => $peopleCount,
            'schools' => $schoolRepository->findAll(),
            'dates' => $usedDates,
            'courses' => PerformanceCourseEnum::cases(),
            'name' => $names[array_rand($names)],
        ]);
    }

    #[Route('/alumni/details/{slug}', name: 'show')]
    public function show(Request $request, string $slug, UserRepository $userRepository, UserProfileRepository $profileRepository, PinboardEntryRepository $entryRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['slug' => $slug]);

        // Wenn der BlogPost nicht gefunden wurde, eine Fehlerseite anzeigen
        if (!$user) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
                'message' => 'Die angeforderte Meldung wurde nicht gefunden.',
            ], new Response('', Response::HTTP_NOT_FOUND));
        }

        $profile = $user->getUserProfiles()->first();
        if($profile != null){

            if(($this->getUser() and $profile->getNetworkState() == 'registered') or ($profile->getNetworkState() == 'public') or ($profile->getUser()->getSchool()->getTitle() === $userRepository->find($this->getUser())->getSchool()->getTitle())){

                $submittedToken = $request->getPayload()->get('token');
                $gruschelButton = true;
                foreach ($user->getGruschels() as $gruschelElement) {
                    if ($gruschelElement->getSendBy() === $this->getUser() and !$gruschelElement->isRead()) {
                        $gruschelButton = false;
                        break;
                    }
                }

                if ($request->isMethod('post') && $this->isCsrfTokenValid('gruscheln', $submittedToken)) {
                    $gruschel = new Gruschel();
                    $gruschel->setUser($user);
                    $gruschel->setSendBy($this->getUser());
                    $gruschel->setIsRead(false);
                    $entityManager->persist($gruschel);
                    $entityManager->flush();
                    $this->addFlash("success","Du hast {$user->getUserProfiles()->first()} gegruschelt.");
                    return $this->redirectToRoute('people_show', ['slug' => $user->getSlug()]);
                }

                if ($request->isMethod('post') && $this->isCsrfTokenValid('pinboard-comment', $submittedToken)) {

                    $ownProfile = $profileRepository->findOneBy(['user' => $this->getUser()]);

                    $pinBoardEntry = new PinboardEntry();
                    $pinBoardEntry->setWriter($ownProfile);
                    $pinBoardEntry->setUserProfile($profile);
                    $pinBoardEntry->setContent($request->request->get('comment'));

                    $entityManager->persist($pinBoardEntry);
                    $entityManager->flush();
                    $this->addFlash("success","Nachricht angepinnt");
                    return $this->redirectToRoute('people_show', ['slug' => $user->getSlug()]);
                }

                $pinBoardEntries = $entryRepository->findBy(['userProfile' => $profile]);

                $link = $profile->getPortfolioLink();

                if ($link) {
                    // Überprüfen, ob der Link bereits mit http:// oder https:// beginnt
                    if (strpos($link, 'http://') !== 0 && strpos($link, 'https://') !== 0) {
                        // Wenn nicht, füge https:// hinzu
                        $link = 'https://' . $link;
                    }

                    // Setze den korrigierten Link zurück
                    $profile->setPortfolioLink($link);
                }

                return $this->render('people/show.html.twig', [
                    'gruschelButton' => $gruschelButton,
                    'person' => $profile,
                    'pinboard_entries' => $pinBoardEntries,
                ]);
            }
        }


        $this->addFlash('warning', 'Person existiert nicht oder nicht mehr.');
        return $this->redirectToRoute('people_index');
    }

    #[Route('/alumni/schule/{bsn}', name: 'school')]
    public function school(School $school, PersonOfferRepository $offerRepository): Response
    {
        $offers = $offerRepository->findBy(['school' => $school]);

        return $this->render('school/show.html.twig', [
            'school' => $school,
            'offers' => $offers,
        ]);
    }
}
