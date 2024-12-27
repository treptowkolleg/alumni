<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRoleFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/user', name: 'user_index')]
    public function index(UserRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $repository->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/role/{id}', name: 'user_role_update')]
    public function roleUpdate(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserRoleFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'User role updated.');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user_role_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
