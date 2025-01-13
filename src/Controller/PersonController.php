<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Enums\PerformanceCourseEnum;
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

    #[Route('/{filter}', name: 'index')]
    public function index(Request $request, UserRepository $userRepository, UserProfileRepository $repository, SchoolRepository $schoolRepository, string $filter = 'all'): Response
    {
        $search = $request->get('person_search');


        $people = null;

        $filteredTypesQuery = trim($request->query->get('schools'),',');
        $filteredDatesQuery = trim($request->query->get('dates'),',');
        $filteredCoursesQuery = trim($request->query->get('courses'),',');
        $filteredTypes = array_filter(explode(',', $filteredTypesQuery));
        $filteredDates = array_filter(explode(',', $filteredDatesQuery));
        $filteredCourses = array_filter(explode(',', $filteredCoursesQuery));

        $usedDates = $repository->findExamDates();

        if($filter == 'all') {
            if (!empty($filteredTypes)) {
                $people = $repository->findBySchool($filteredTypes,$search);
            } else {
                $people = $repository->findBySearchQuery($search);
            }

        }


        if($filter == 'school') {
            if($this->isGranted('ROLE_USER')) {
                $user = $userRepository->find($this->getUser());
                $people = $repository->findBySchool([$user->getSchool()->getTitle()],$search);
                $filteredTypes = null;
            } else {
                return $this->redirectToRoute('people_index');
            }
        }


        return $this->render('people/index.html.twig', [
            'filter' => $filter,
            'people' => $people,
            'schools' => $schoolRepository->findAll(),
            'dates' => $usedDates,
            'courses' => PerformanceCourseEnum::cases(),
            'filtered_schools' => $filteredTypes,
            'filtered_dates' => $filteredDates,
            'filtered_courses' => $filteredCourses,
        ]);
    }

    #[Route('/details/{slug}', name: 'show')]
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
}
