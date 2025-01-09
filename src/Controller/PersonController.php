<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Entity\UserProfile;
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
        $filteredTypesQuery = trim($request->query->get('schools'),',');
        $filteredDatesQuery = trim($request->query->get('dates'),',');
        $filteredTypes = array_filter(explode(',', $filteredTypesQuery));
        $filteredDates = array_filter(explode(',', $filteredDatesQuery));
        if (!empty($filteredTypes)) {
            $people = $repository->findBySchool($filteredTypes);
        } else {
            $people = $repository->findAll();
        }

        $usedDates = $repository->findExamDates();


        return $this->render('people/index.html.twig', [
            'people' => $people,
            'schools' => $schoolRepository->findAll(),
            'dates' => $usedDates,
            'filtered_schools' => $filteredTypes,
            'filtered_dates' => $filteredDates,
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(UserProfile $userProfile): Response
    {
        return $this->render('people/show.html.twig', [
            'person' => $userProfile,
        ]);
    }
}
