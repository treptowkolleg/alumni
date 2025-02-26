<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\BlogType;
use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\Inbound;
use App\Entity\Newsletter;
use App\Entity\PinboardEntry;
use App\Entity\School;
use App\Entity\User;
use App\Entity\UserProfile;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
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
            ->setTitle('Alumni Portal')
            ->setFaviconPath('favicon.svg')
            ;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('build/admin.css')
            ->addCssFile('build/webfont/tabler-icons.min.css')
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Übersicht');
        yield MenuItem::linkToRoute('Zurück zur Website', 'ti ti-world-www', 'app_index');
        yield MenuItem::linkToDashboard('Dashboard', 'ti ti-dashboard');


        yield MenuItem::section('Redaktion');
        yield MenuItem::linkToCrud('Beiträge', 'ti ti-article',BlogPost::class)
            ->setCssClass('blog-link')
            ->setPermission('ROLE_AUTHOR')
        ;
        yield MenuItem::linkToCrud('Neuer Beitrag', 'ti ti-pencil-plus',BlogPost::class)
            ->setCssClass('blog-link')
            ->setAction('new')
            ->setPermission('ROLE_AUTHOR')
        ;
        yield MenuItem::linkToCrud('Kategorien', 'ti ti-category',BlogType::class)
            ->setCssClass('blog-link')
            ->setPermission('ROLE_AUTHOR')
        ;

        yield MenuItem::linkToCrud('Veranstaltungen', 'ti ti-calendar',Event::class)
            ->setCssClass('event-link')
            ->setPermission('ROLE_PLANNER')
        ;
        yield MenuItem::linkToCrud('Neue Veranstaltung', 'ti ti-calendar-plus',Event::class)
            ->setCssClass('event-link')
            ->setAction('new')
            ->setPermission('ROLE_PLANNER')
        ;
        yield MenuItem::linkToCrud('Veranstaltungsarten', 'ti ti-ticket',EventType::class)
            ->setCssClass('event-link')
            ->setPermission('ROLE_PLANNER')
        ;

        yield MenuItem::section('Newsletter');


        yield MenuItem::section('Moderation');
        yield MenuItem::linkToCrud('inbound', 'ti ti-mailbox',Inbound::class)
            ->setCssClass('moderation-link')
            ->setPermission('ROLE_MODERATION')
        ;
        yield MenuItem::linkToCrud('PinBoardEntries', 'ti ti-pin',PinboardEntry::class)
            ->setCssClass('moderation-link')
            ->setPermission('ROLE_MODERATION')
        ;

        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Benutzer', 'ti ti-users',User::class)
            ->setCssClass('admin-link')
            ->setPermission('ROLE_ADMIN')
        ;
        yield MenuItem::linkToCrud('E-Mail-Adressen', 'ti ti-mail',Newsletter::class)
            ->setCssClass('admin-link')
            ->setPermission('ROLE_ADMIN')
        ;
        yield MenuItem::linkToCrud('Schulen', 'ti ti-school',School::class)
            ->setCssClass('admin-link')
            ->setPermission('ROLE_ADMIN')
        ;
    }
}
