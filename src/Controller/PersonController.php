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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/personen', name: 'people_', methods: ['GET','POST'])]
class PersonController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(Request $request, UserProfileRepository $repository, SchoolRepository $schoolRepository): Response
    {
        $search = $request->get('person_search');

        $filteredTypesQuery = trim($request->query->get('schools'),',');
        $filteredDatesQuery = trim($request->query->get('dates'),',');
        $filteredCoursesQuery = trim($request->query->get('courses'),',');
        $filteredTypes = array_filter(explode(',', $filteredTypesQuery));
        $filteredDates = array_filter(explode(',', $filteredDatesQuery));
        $filteredCourses = array_filter(explode(',', $filteredCoursesQuery));
        if (!empty($filteredTypes)) {
            $people = $repository->findBySchool($filteredTypes,$search);
        } else {
            $people = $repository->findBySearchQuery($search);
        }

        $usedDates = $repository->findExamDates();


        return $this->render('people/index.html.twig', [
            'people' => $people,
            'schools' => $schoolRepository->findAll(),
            'dates' => $usedDates,
            'courses' => PerformanceCourseEnum::cases(),
            'filtered_schools' => $filteredTypes,
            'filtered_dates' => $filteredDates,
            'filtered_courses' => $filteredCourses,
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(User $user): Response
    {
        $profile = $user->getUserProfiles()->first();
        if($profile != null){
            if(($this->getUser() and $profile->getNetworkState() == 'registered') or ($profile->getNetworkState() == 'public')){
                return $this->render('people/show.html.twig', [
                    'person' => $profile,
                ]);
            }
        }
        $this->addFlash('warning', 'Person existiert nicht oder nicht mehr.');
        return $this->redirectToRoute('people_index');
    }
}
