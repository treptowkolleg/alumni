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

    #[Route('/{filter}/{page}', name: 'index')]
    public function index(Request $request, UserRepository $userRepository, UserProfileRepository $repository, SchoolRepository $schoolRepository, string $filter = 'all', int $page = 1): Response
    {
        $filterValues = $request->request->all();
        $peopleCount = 0;
        $search = $request->request->get('person');
        $schools = $request->request->all('school');
        $courses = $request->request->all('course');
        $startDate = $request->request->get('start_date');
        $endDate = $request->request->get('end_date');

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
                    $people = $repository->findBySearchQuery($name, $firstname, [$school->getId()], $courses, $startDate, $endDate, offset: $page);
                    $peopleCount = count($repository->findBySearchQuery($name, $firstname, [$school->getId()], $courses, $startDate, $endDate));
                    $filterValues['school'] = [$school->getId()];
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
                    $peopleCount = count($repository->findBySearchQuery($name, $firstname, $schools, $courses, $startDate, $endDate));
                }
                else {
                    $people = [];
                }
            } else {
                return $this->redirectToRoute('people_index');
            }
        }


        return $this->render('people/index.html.twig', [
            'filterValues' => $filterValues,
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
}
