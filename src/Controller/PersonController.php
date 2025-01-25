<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
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

    #[Route('/{filter}', name: 'index')]
    public function index(Request $request, UserRepository $userRepository, UserProfileRepository $repository, SchoolRepository $schoolRepository, string $filter = 'all'): Response
    {
        $filterValues = $request->request->all();

        $search = $request->request->get('person');

        // TODO: SoundEx lieber erst während der DB-Query
        $searchArray = explode(' ', $search);
        if(count($searchArray) > 1){
            $name = SoundExpression::generate(array_pop($searchArray));
            $firstname = SoundExpression::generate(implode(' ', $searchArray));
        } else {
            $firstname = false;
            $name = SoundExpression::generate(array_pop($searchArray));
        }


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
                $people = $repository->findBySearchQuery($name, $firstname);
            }

        }

        if($search) {
            $people = array_filter($people, function($person) use ($firstname, $name) {
                if($firstname) {
                    if(
                        levenshtein($firstname,$person->getUser()->getFirstnameSoundEx()) <= 2 and
                        levenshtein($name, $person->getUser()->getLastnameSoundEx()) <= 2
                    ) {
                        return $person;
                    }
                } else {
                    if(
                        levenshtein($name,$person->getUser()->getFirstnameSoundEx()) <= 2 or
                        levenshtein($name, $person->getUser()->getLastnameSoundEx()) <= 2
                    ) {
                        return $person;
                    }
                }
                return null;
            });
        }


        if($filter == 'school') {
            if($this->isGranted('ROLE_USER')) {
                $user = $userRepository->find($this->getUser());
                if($school = $user->getSchool()) {
                    $people = $repository->findBySchool([$school->getTitle()],$search);
                } else {
                    $this->addFlash('warning','Du hast dich keiner Bildungseinrichtung zugeordnet!');
                    return $this->redirectToRoute('people_index');
                }
                $filteredTypes = null;
            } else {
                return $this->redirectToRoute('people_index');
            }
        }

        if($filter == 'freunde') {
            if($this->isGranted('ROLE_USER')) {
                $user = $userRepository->find($this->getUser());
                if($result = $user->getUserProfiles()->first())
                    $people = $result->getUserProfiles();
                else
                    $people = [];
                $filteredTypes = null;
            } else {
                return $this->redirectToRoute('people_index');
            }
        }


        return $this->render('people/index.html.twig', [
            'sx' => $firstname . " " . $name,
            'filterValues' => $filterValues,
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
