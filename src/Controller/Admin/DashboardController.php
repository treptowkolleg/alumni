<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\BlogType;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\UserProfile;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();


        // ...set chart data and options somehow

        return $this->render('admin/dashboard.html.twig', [
            'title' => 'test',
        ]);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Alumni Portal');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Website');
        yield MenuItem::linkToRoute('Startseite', 'fa fa-home', 'app_index');
        yield MenuItem::section('Administration');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Content Management');
        yield MenuItem::linkToCrud('Beiträge', 'fa fa-file',BlogPost::class);
        yield MenuItem::linkToCrud('Events', 'fa fa-list',Event::class);

        yield MenuItem::section('Benutzerverwaltung');
        yield MenuItem::linkToCrud('Benutzer', 'fa fa-users',User::class);
        yield MenuItem::linkToCrud('Alumni-Profile', 'fa fa-users',UserProfile::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
