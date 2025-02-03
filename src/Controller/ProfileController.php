<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Enums\PerformanceCourseEnum;
use App\Form\NewsLetterToggleFormType;
use App\Form\UserImageType;
use App\Form\UserprofileFormType;
use App\Repository\NewsletterRepository;
use App\Repository\SchoolRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(Request $request, UserProfileRepository $userProfileRepository, NewsletterRepository $newsletterRepository, EntityManagerInterface $entityManager): Response
    {
        $userProfile = $userProfileRepository->findBy(['user' => $this->getUser()]);
        $newsletter = $newsletterRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        $form = $this->createForm(NewsLetterToggleFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($newsletter) {
                $entityManager->remove($newsletter);
                $this->addFlash('success','Newsletter erfolgreich abbestellt.');
            } else {
                $newsletter = new Newsletter();
                $newsletter->setEmail($this->getUser()->getUserIdentifier());
                $entityManager->persist($newsletter);
                $this->addFlash('success','Newsletter erfolgreich abonniert.');
            }
            $entityManager->flush();
            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form,
            'controller_name' => 'ProfileController',
            'user_profile' => $userProfile,
            'has_newsletter' => $newsletter,
        ]);
    }

    #[Route('/update', name: 'profile_new', methods: ['GET', 'POST'])]
    public function profileNew(Request $request, UserProfileRepository $profileRepository, EntityManagerInterface $entityManager): Response
    {
        $profile = $profileRepository->findOneBy(['user' => $this->getUser()]);
        if(!$profile) {
            $profile = new UserProfile();
            $profile->setUser($this->getUser());
        }
        $form = $this->createForm(UserprofileFormType::class, $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($profile);
            $entityManager->flush();
            $this->addFlash('success', 'Dein Alumni-Profil wurde erfolgreich gespeichert.');
            return $this->redirectToRoute('profile_index');
        }
        return $this->render('profile/profile_new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/image/update', name: 'image_new', methods: ['GET', 'POST'])]
    public function imageUpload(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($this->getUser());
        $form = $this->createForm(UserImageType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Dein Profilbild wurde erfolgreich aktualisiert.');
            return $this->redirectToRoute('profile_image_new');
        }
        return $this->render('profile/image_update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/settings', name: 'settings', methods: ['GET', 'POST'])]
    public function settings(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($this->getUser());

        $cookies = $request->cookies->all();

        return $this->render('profile/settings.html.twig', [
            'form' => null,
            'cookies' => $cookies,
        ]);
    }

    #[Route('/schule/{page}', name: 'school')]
    public function school(Request $request, UserRepository $userRepository, UserProfileRepository $repository, SchoolRepository $schoolRepository, int $page = 1): Response
    {
        if($request->isMethod('POST')) {
            $filterValues = $request->request->all();
            $request->getSession()->set('filter_school', $filterValues);
        }

        $search = $request->getSession()->get('filter_school')['person'] ?? null;
        $courses = $request->getSession()->get('filter_school')['course'] ?? null;
        $startDate = $request->getSession()->get('filter_school')['start_date'] ?? null;
        $endDate = $request->getSession()->get('filter_school')['end_date'] ?? null;

        $searchArray = explode(' ', $search);
        if(count($searchArray) > 1){
            $name = array_pop($searchArray);
            $firstname = implode(' ', $searchArray);
        } else {
            $firstname = false;
            $name = array_pop($searchArray);
        }

        $usedDates = $repository->findExamDates();


        if($this->isGranted('ROLE_USER')) {
            $user = $userRepository->find($this->getUser());
            if($school = $user->getSchool()) {
                $filtered = $request->getSession()->get('filter_school');
                $filtered['school'] = [$school->getId()];
                $request->getSession()->set('filter_school',$filtered);
                $people = $repository->findBySearchQuery($name, $firstname, [$school->getId()], $courses, $startDate, $endDate, offset: $page);
                $peopleCount = count($repository->findBySearchQuery($name, $firstname, [$school->getId()], $courses, $startDate, $endDate));
            } else {
                $this->addFlash('warning','Du hast dich keiner Bildungseinrichtung zugeordnet!');
                return $this->redirectToRoute('people_index');
            }
        } else {
            return $this->redirectToRoute('people_index');
        }


        return $this->render('people/school.html.twig', [
            'filterValues' => $request->getSession()->get('filter_school'),
            'filter' => 'school',
            'page' => $page,
            'people' => $people,
            'people_count' => $peopleCount,
            'schools' => $schoolRepository->findAll(),
            'dates' => $usedDates,
            'courses' => PerformanceCourseEnum::cases(),
        ]);
    }

}
