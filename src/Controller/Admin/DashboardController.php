<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\BlogType;
use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\Hobby;
use App\Entity\HobbyCategory;
use App\Entity\Inbound;
use App\Entity\Interest;
use App\Entity\InterestCategory;
use App\Entity\JobType;
use App\Entity\Newsletter;
use App\Entity\NewsletterQueue;
use App\Entity\NewsletterTemplate;
use App\Entity\OfferType;
use App\Entity\PersonOffer;
use App\Entity\PinboardEntry;
use App\Entity\SalaryLevel;
use App\Entity\School;
use App\Entity\Survey;
use App\Entity\University;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\BlogPostRepository;
use App\Repository\SurveyRepository;
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
        private SurveyRepository $surveyRepository,
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
        $surveys = $this->surveyRepository->findBy(['active' => true]);

        $filters = [
            '.php',
            '.txt',
            '.jsp',
            '.js',
            '()',
            '@',
            '|',
            '\'',
            '"',
            '.html',
            '%20',
            'XOR',
            'AND',
            'OR',
            '&',
            'force',
            'install',
            'setup',
            'config',
            'xmlrpc',
            'robots',
            'vendor',
            'root',
            'client',
            'auth',
            'lang',
            'wp-admin',
            'adminer',
            'admin-login',
            'administrator',
            'cpanel',
            'phpmyadmin',
            'private',
            'backup',
            'temp',
            'test',
            'debug',
            'env',
            'git',
            'svn',
            'shell',
            'upload',
            'include',
            'backup',
            'database',
            'db',
            'sql',
            'dump',
            'secret',
            'hidden',
            'pass',
            'password',
            'api',
            'old',
            'new',
            'tmp',
            'cache',
            'portal',
            'portaladmin',
            'console',
            'script',
            'scripts',
            'cgi',
            'exploit',
            'hacker',
            'malware',
            'bot',
            'crawler',
            'scanner',
            '.'
        ];

        $serverStats = [
            'php_version' => phpversion(),
            'symfony_version' => \Symfony\Component\HttpKernel\Kernel::VERSION,
            'memory_limit' => ini_get('memory_limit'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time'),
            'os' => php_uname(),
            'hostname' => gethostname(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'CLI',
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'N/A',
            'timezone' => date_default_timezone_get(),
            'sapi' => php_sapi_name(),
            'load_1' => function_exists('sys_getloadavg') ? sys_getloadavg()[0] : 'N/A',
            'load_5' => function_exists('sys_getloadavg') ? sys_getloadavg()[1] : 'N/A',
            'load_15' => function_exists('sys_getloadavg') ? sys_getloadavg()[2] : 'N/A',
            'disk_free_mb' => round(disk_free_space('/') / 1024 / 1024, 2),
            'disk_total_mb' => round(disk_total_space('/') / 1024 / 1024, 2),
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        ];

        $yearMonth = date('Y-m'); // z.B. "2025-05"

        $filename = $this->getParameter('kernel.project_dir') . '/var/log/url_tracking_'.$yearMonth.'.csv';

        $countsByDate = [];
        $userPerDay = [];

        $stati = ["200","301","302","404", "405","500"];
        $targets = [];


        $year = date('Y');
        $month = date('m');
        $daysInMonth = (int)date('t');
        $fraud = [];

        foreach ($stati as $state) {
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dt = \DateTime::createFromFormat('Y-m-d', "$year-$month-$day");
                $countsByDate[$dt->format('d.m.')][$state] = 0;
                $userPerDay[$dt->format('d.m.')] = [];
                $targets[$dt->format('d.m.')] = [];
                $fraud[$dt->format('d.m.')] = 0;

            }
        }



        if (($handle = fopen($filename, "r")) !== false) {
            // Header auslesen, falls vorhanden
            $header = fgetcsv($handle, 1000, ";"); // oder "," je nach Trennzeichen

            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                // Beispiel: Timestamp in Spalte 1 (Index 0 oder 1 je nach CSV)
                $clientIp = $data[0];
                $timestamp = $data[1]; // oder z.B. $data[1], je nach Aufbau
                $status = $data[5]; // oder z.B. $data[1], je nach Aufbau
                $target = $data[3];

                $userPerDay[$day][$clientIp] = 0;

                // Timestamp parsen: Format "d.m.Y H:i"
                $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $timestamp);
                if (!$dt) {
                    continue; // Ungültiges Datum überspringen
                }

                // Tag als Y-m-d (z.B. 2025-05-16)
                $day = $dt->format('d.m.');


                if (!isset($countsByDate[$day])) {
                    if (count($countsByDate) >= 30) {
                        break; // Abbruch, wenn schon 30 Tage gesammelt
                    }
                    $countsByDate[$day][$status] = 0;
                }
                $countsByDate[$day][$status]++;
                $targets[$day][] = $target;
            }
            fclose($handle);
            $usersPerDay = [];
            foreach ($userPerDay as $day => $content) {
                $usersPerDay[$day] = count($content);
            }

            array_pop($usersPerDay);
            $labels = array_keys($countsByDate);

            $data = array_values($countsByDate);

            array_multisort($labels, SORT_ASC, $data);
        }

        $stateOk = [];
        $stateNotFound = [];
        $stateServerError = [];
        $stateRedirect = [];
        $sum = [];


        foreach ($data ?? [] as $date => $values) {
            $stateOk[$date] = $values["200"];
            $stateNotFound[$date] = $values["404"];
            $stateServerError[$date] = $values["500"];
            $stateRedirect[$date] = $values["302"];
            $sum[$date] = $values["200"] + $values["404"] + $values["500"] + $values["302"];


// Haupt-Logik
            $fraud = array_map(function ($targetList) use ($filters) {
                return $this->countFilterMatches($targetList, $filters);
            }, $targets);


        }

        return $this->render('admin/dashboard.html.twig', [
            'title' => 'test',
            'surveys' => $surveys,
            'posts' => $posts,
            'news_articles' => $newsPosts,
            'serverStats' => $serverStats,
            'labels' => $labels ?? [],
            'data' => $data ?? [],
            'stateOk' => $stateOk ?? [],
            'stateNotFound' => $stateNotFound ?? [],
            'stateServerError' => $stateServerError ?? [],
            'stateRedirect' => $stateRedirect ?? [],
            'userPerDay' => $usersPerDay ?? [],
            'sum' => $sum ?? [],
            'fraud' => $fraud ?? [],
            'targets' => $targets ?? [],
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

    private function countFilterMatches(array $values, array $filters): int {
        $count = 0;
        foreach ($values as $value) {
            foreach ($filters as $filter) {
                if (str_contains($value, $filter)) {
                    $count++;
                    break;
                }
            }
        }
        return $count;
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
            yield MenuItem::linkToCrud('Surveys', 'ti ti-question-mark',Survey::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;

            yield MenuItem::linkToCrud('Hobbies', 'ti ti-roller-skating',Hobby::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
            yield MenuItem::linkToCrud('Hobby Categories', 'ti ti-category',HobbyCategory::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
            yield MenuItem::linkToCrud('Interests', 'ti ti-template',Interest::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
            yield MenuItem::linkToCrud('Interest Categories', 'ti ti-category',InterestCategory::class)
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
            yield MenuItem::linkToCrud('University', 'ti ti-school',University::class)
                ->setCssClass('admin-link')
                ->setPermission('ROLE_ADMIN')
            ;
        }

    }
}
