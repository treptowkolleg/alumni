<?php

namespace App\Controller;

use App\Entity\GroupSubject;
use App\Entity\Hobby;
use App\Entity\Interest;
use App\Entity\SubjectPost;
use App\Form\GroupSubjectType;
use App\Form\SubjectPostType;
use App\Repository\HobbyCategoryRepository;
use App\Repository\InterestCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gruppen', name: 'group_')]
class GroupController extends AbstractController
{

    #[Route('', name: 'hobby_index')]
    public function hobby_index(HobbyCategoryRepository $hobbyCategoryRepository, InterestCategoryRepository $interestCategoryRepository): Response
    {
        $hobbyCategories = $hobbyCategoryRepository->findAll();
        $interestCategories = $interestCategoryRepository->findAll();

        return $this->render('group/hobby/index.html.twig', [
            'hobby_categories' => $hobbyCategories,
            'interest_categories' => $interestCategories
        ]);
    }

    #[Route('/hobby/{slug}', name: 'hobby_show')]
    public function hobbyShow(Request $request, Hobby $hobby, EntityManagerInterface $entityManager): Response
    {
        $groupSubject = new GroupSubject();
        $form = $this->createForm(GroupSubjectType::class, $groupSubject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupSubject->setOwner($this->getUser());
            $groupSubject->setPrivate(false);
            $groupSubject->setHobby($hobby);

            $entityManager->persist($groupSubject);
            $entityManager->flush();
            $this->addFlash('success', 'Thema wurde erfolgreich erstellt.');
            return $this->redirectToRoute('group_hobby_show', ['slug' => $hobby->getSlug()]);
        }

        return $this->render('group/hobby/show.html.twig', [
            'element' => $hobby,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/interesse/{slug}', name: 'interest_show')]
    public function interestShow(Request $request, Interest $interest, EntityManagerInterface $entityManager): Response
    {
        $groupSubject = new GroupSubject();
        $form = $this->createForm(GroupSubjectType::class, $groupSubject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupSubject->setOwner($this->getUser());
            $groupSubject->setPrivate(false);
            $groupSubject->setInterest($interest);

            $entityManager->persist($groupSubject);
            $entityManager->flush();
            $this->addFlash('success', 'Thema wurde erfolgreich erstellt.');
            return $this->redirectToRoute('group_interest_show', ['slug' => $interest->getSlug()]);
        }

        return $this->render('group/hobby/show.html.twig', [
            'element' => $interest,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/plausch/{slug}', name: 'talk_show')]
    public function talkShow(Request $request, GroupSubject $subject, EntityManagerInterface $entityManager): Response
    {
        $subjectPost = new SubjectPost();
        $form = $this->createForm(SubjectPostType::class, $subjectPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subjectPost->setOwner($this->getUser());
            $subjectPost->setSubject($subject);
            $subjectPost->setParent(null);

            $entityManager->persist($subjectPost);
            $entityManager->flush();
            $this->addFlash('success', 'Beitrag wurde erfolgreich veröffentlicht.');
            return $this->redirectToRoute('group_talk_show', ['slug' => $subject->getSlug()]);
        }

        return $this->render('group/subject/show.html.twig', [
            'element' => $subject,
            'form' => $form->createView(),
        ]);
    }

}