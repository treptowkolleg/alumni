<?php

namespace App\Controller;

use App\Repository\HobbyCategoryRepository;
use App\Repository\InterestCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gruppen', name: 'group_')]
class GroupController extends AbstractController
{

    #[Route('/hobbies', name: 'hobby_index')]
    public function hobby_index(HobbyCategoryRepository $hobbyCategoryRepository, InterestCategoryRepository $interestCategoryRepository): Response
    {
        $hobbyCategories = $hobbyCategoryRepository->findAll();
        $interestCategories = $interestCategoryRepository->findAll();

        return $this->render('group/hobby/index.html.twig', [
            'hobby_categories' => $hobbyCategories,
            'interest_categories' => $interestCategories
        ]);
    }

}