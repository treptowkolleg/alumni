<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\BlogType;
use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\Inbound;
use App\Entity\JobType;
use App\Entity\Newsletter;
use App\Entity\NewsletterQueue;
use App\Entity\NewsletterTemplate;
use App\Entity\OfferType;
use App\Entity\PersonOffer;
use App\Entity\PinboardEntry;
use App\Entity\SalaryLevel;
use App\Entity\School;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\BlogPostRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Menu\SubMenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\DomCrawler\Field\FormField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{


    public function __construct(
        private BlogPostRepository $repository,
        private UserRepository $userRepository,
    )
    {
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $userMenu = parent::configureUserMenu($user)->setGravatarEmail($user->getUserIdentifier());

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $subMenu = MenuItem::section('Benutzerimitation');
            $users = $this->userRepository->findAll();


            foreach ($users as $targetUser) {
                if ($targetUser->getUserIdentifier() !== $user->getUserIdentifier() and count($targetUser->getRoles()) > 1 and !in_array('ROLE_ADMIN', $targetUser->getRoles())) {
                    $userMenu->addMenuItems([
                        MenuItem::linkToUrl(
                        $targetUser->getFullname(),
                        'fa fa-exchange',
                        '/admin?_switch_user=' . $targetUser->getUserIdentifier()
                    )
                    ]);
                }
            }
            $userMenu->addMenuItems([$subMenu]);
        }

        return $userMenu;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $posts = $this->repository->findPublishedBlogPosts(user: $this->getUser());
        $newsPosts = $this->repository->findPublishedNews(user: $this->getUser());


        return $this->render('admin/dashboard.html.twig', [
            'title' => 'test',
            'posts' => $posts,
            'news_articles' => $newsPosts,
            'server_time' => new \DateTimeImmutable("now")
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
            ->setTitle('<div class="d-flex align-items-end"><img src="/favicon.svg" height="20" class="me-2" alt="Alumni Portal Logo"><div style="line-height: 1">Administration</div></div> ')
            ->setFaviconPath('favicon.svg')
            ->renderContentMaximized()
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
        yield MenuItem::linkToRoute('Zurück zur Website', 'ti ti-world-www', 'app_index');
        yield MenuItem::linkToDashboard('Dashboard', 'ti ti-dashboard');


        if($this->isGranted('ROLE_AUTHOR') or $this->isGranted('ROLE_PLANNER')) {
            yield MenuItem::section('Redaktion');
            yield MenuItem::linkToCrud('Neuer Beitrag', 'ti ti-pencil-plus',BlogPost::class)
                ->setCssClass('blog-link')
                ->setAction('new')
                ->setPermission('ROLE_AUTHOR')
            ;
            yield MenuItem::linkToCrud('Alle Beiträge', 'ti ti-article',BlogPost::class)
                ->setCssClass('blog-link')
                ->setPermission('ROLE_AUTHOR')
            ;
            yield MenuItem::linkToCrud('Kategorien', 'ti ti-category',BlogType::class)
                ->setCssClass('blog-link')
                ->setPermission('ROLE_AUTHOR')
            ;
            yield MenuItem::linkToCrud('Neue Veranstaltung', 'ti ti-calendar-plus',Event::class)
                ->setCssClass('event-link')
                ->setAction('new')
                ->setPermission('ROLE_PLANNER')
            ;
            yield MenuItem::linkToCrud('Alle Veranstaltungen', 'ti ti-calendar',Event::class)
                ->setCssClass('event-link')
                ->setPermission('ROLE_PLANNER')
            ;
            yield MenuItem::linkToCrud('Veranstaltungsarten', 'ti ti-ticket',EventType::class)
                ->setCssClass('event-link')
                ->setPermission('ROLE_PLANNER')
            ;
            yield MenuItem::linkToCrud('inbound', 'ti ti-mailbox',Inbound::class)
                ->setCssClass('moderation-link')
                ->setPermission('ROLE_SUPER_MODERATION')
            ;
            yield MenuItem::linkToCrud('PinBoardEntries', 'ti ti-pin',PinboardEntry::class)
                ->setCssClass('moderation-link')
                ->setPermission('ROLE_SUPER_MODERATION')
            ;
        }


        if($this->isGranted('ROLE_SCHOOL')) {
            yield MenuItem::section('Newsletter');
            yield MenuItem::linkToCrud('Strukturvorlagen', 'ti ti-news',NewsletterTemplate::class)
                ->setCssClass('moderation-link')
                ->setPermission('ROLE_SCHOOL')
            ;
            yield MenuItem::linkToCrud('Warteschlange', 'ti ti-clock-2',NewsletterQueue::class)
                ->setCssClass('moderation-link')
                ->setPermission('ROLE_ADMIN')
            ;

            yield MenuItem::section('Angebote');
            yield MenuItem::linkToCrud('Angebote', 'ti ti-school',PersonOffer::class)
                ->setCssClass('moderation-link')
                ->setPermission('ROLE_SCHOOL')
            ;
        }

        if($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Administration');
            yield MenuItem::linkToCrud('Benutzer', 'ti ti-users',User::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
            yield MenuItem::linkToCrud('E-Mail-Adressen', 'ti ti-mail',Newsletter::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
            yield MenuItem::linkToCrud('Angebotstypen', 'ti ti-school',OfferType::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
            yield MenuItem::linkToCrud('JobTypen', 'ti ti-briefcase',JobType::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
            yield MenuItem::linkToCrud('SalaryLevels', 'ti ti-cash-register',SalaryLevel::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
            yield MenuItem::linkToCrud('Schulen', 'ti ti-school',School::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
        }

    }
}
