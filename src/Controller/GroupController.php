<?php

namespace App\Controller;

use App\Entity\Hobby;
use App\Entity\Interest;
use App\Repository\HobbyCategoryRepository;
use App\Repository\InterestCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function hobbyShow(Hobby $hobby): Response
    {
        return $this->render('group/hobby/show.html.twig', [
            'element' => $hobby,
        ]);
    }

    #[Route('/interesse/{slug}', name: 'interest_show')]
    public function interestShow(Interest $interest): Response
    {
        return $this->render('group/hobby/show.html.twig', [
            'element' => $interest,
        ]);
    }

}