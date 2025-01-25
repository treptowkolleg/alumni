<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\NewsLetterToggleFormType;
use App\Form\UserImageType;
use App\Form\UserprofileFormType;
use App\Repository\NewsletterRepository;
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

}
