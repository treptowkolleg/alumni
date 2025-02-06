<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Entity\School;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Enums\PerformanceCourseEnum;
use App\Operator\SoundExpression;
use App\Repository\BlogPostRepository;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use App\Repository\SchoolRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        ]);
    }

    #[Route('/alumni/details/{slug}', name: 'show')]
    public function show(User $user, UserRepository $userRepository): Response
    {
        $profile = $user->getUserProfiles()->first();
        if($profile != null){
            if(($this->getUser() and $profile->getNetworkState() == 'registered') or ($profile->getNetworkState() == 'public') or ($profile->getUser()->getSchool()->getTitle() === $userRepository->find($this->getUser())->getSchool()->getTitle())){
                return $this->render('people/show.html.twig', [
                    'person' => $profile,
                ]);
            }
        }
        $this->addFlash('warning', 'Person existiert nicht oder nicht mehr.');
        return $this->redirectToRoute('people_index');
    }

    #[Route('/alumni/schule/{bsn}', name: 'school')]
    public function school(School $school): Response
    {
        return $this->render('school/show.html.twig', [
            'school' => $school,
        ]);
    }
}
