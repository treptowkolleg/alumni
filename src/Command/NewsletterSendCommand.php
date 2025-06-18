<?php

namespace App\Command;

use App\Entity\BlogPost;
use App\Entity\Event;
use App\Entity\NewsletterQueue;
use App\Entity\NewsletterTemplate;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\NewsletterTemplateRepository;
use App\Repository\UserRepository;
use DatePeriod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

#[AsCommand(
    name: 'app:newsletter-send',
    description: 'Add a short description for your command',
)]
class NewsletterSendCommand extends Command
{
    private EntityManagerInterface $em;
    private MailerInterface $mailer;

    private Packages $assets;
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer, Packages $assets)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
        $this->assets = $assets;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        date_default_timezone_set('Europe/Berlin');

        $logoUrl = $this->assets->getUrl('images/logo.svg');
        $projectDir = $this->getApplication()->getKernel()->getProjectDir();
        $logoPath = $projectDir . '/public' . parse_url($logoUrl, PHP_URL_PATH);

        $svgBase64 = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($logoPath));

        $queue = $this->em->getRepository(NewsletterQueue::class)->findBy(['send' => false], orderBy: ['send' => 'ASC'], limit: 10);

        foreach ($queue as $receiver) {
            $now = new \DateTimeImmutable();

            $showEvents = $receiver->getTemplate()->isShowEvents();
            $showBlogs = $receiver->getTemplate()->isShowRecentPosts();
            $showMessages = $receiver->getTemplate()->isShowRecentNews();

            $blogs = [];
            if($showBlogs) {
                $blogs = $this->em->getRepository(BlogPost::class)->findBy(['isPublished' => true], ['updatedAt' => 'DESC'], 4);
            }

            $events = [];
            if($showEvents) {
                $events = $this->em->getRepository(Event::class)->findBy([], ['startDate' => 'DESC'], 4);
            }

            if($receiver->getSendDate() <= $now) {
                $unsubscribeLink = 'https://alumni-portal.org/unsubscribe?email='.urlencode($receiver->getReceiverEmail()).'&token='.urlencode($receiver->getToken());
                if(!$receiver->getTemplate()->isUseAllReceivers()) {
                    $user = $this->em->getRepository(User::class)->findOneBy(['email' => $receiver->getReceiverEmail()]);

                    $messages = $user?->getSendDirectMessages();
                    $messageCount = 0;
                    if($messages) {
                        foreach ($messages as $message) {
                            if($message->getRecipient() == $user and !$message->isRead()) {
                                $messageCount++;
                            }
                        }
                    }

                    $email = (new TemplatedEmail())
                        ->from(new Address('service@alumni-portal.org', 'Alumni-Portal'))
                        ->to((string) $receiver->getReceiverEmail())
                        ->subject($receiver->getTemplate()->getTitle())
                        ->htmlTemplate('newsletter/default.html.twig')
                        ->context([
                            'user' => $user,
                            'config' => $receiver->getTemplate(),
                            'user_count' => $receiver->getUserCount(),
                            'svg_base64' => $svgBase64,
                            'logo_url' => $logoUrl,
                            'show_events' => $showEvents,
                            'events' => $events,
                            'show_blogs' => $showBlogs,
                            'blogs' => $blogs,
                            'show_messages' => $showMessages,
                            'message_count' => $messageCount,
                            'unsubscribe_link' => $unsubscribeLink,
                        ])
                    ;
                } else {
                    // Allgemeine E-Mail für Empfänger ohne Konto
                    $email = (new TemplatedEmail())
                        ->from(new Address('service@alumni-portal.org', 'Alumni-Portal'))
                        ->to((string) $receiver->getReceiverEmail())
                        ->subject($receiver->getTemplate()->getTitle())
                        ->htmlTemplate('newsletter/default.html.twig')
                        ->context([
                            'user' => null,
                            'config' => $receiver->getTemplate(),
                            'svg_base64' => $svgBase64,
                            'logo_url' => $logoUrl,
                            'show_events' => $showEvents,
                            'events' => $events,
                            'show_blogs' => $showBlogs,
                            'blogs' => $blogs,
                            'show_messages' => $showMessages,
                            'message_count' => null,
                            'unsubscribe_link' => $unsubscribeLink,
                        ])
                    ;
                }


                try {
                    $email->getHeaders()->addTextHeader('List-Unsubscribe', '<'.$unsubscribeLink.'>');
                    $email->returnPath('service@alumni-portal.org');
                    $this->mailer->send($email);
                    $receiver->setSend(true);
                } catch (\Exception|TransportExceptionInterface $e) {
                    $receiver->setSend(false);

                    $message = $e->getMessage();

                    // Versuche, SMTP-Code mit Regex zu extrahieren
                    preg_match('/\b(5\d{2})\b.*?(\b5\.\d\.\d\b)?/', $message, $matches);

                    $smtpCode = $matches[1] ?? null; // z. B. 550
                    $smtpStatus = $matches[2] ?? null; // z. B. 5.1.1

                    $output->writeln($message);

                    // Prüfen auf bekannte Codes für "Empfänger existiert nicht"
                    if (in_array($smtpCode, ['550', '553', '554']) || $smtpStatus === '5.1.1') {
                        $output->writeln('Empfänger gelöscht wegen SMTP-Code ' . $smtpCode . ': ' . $receiver->getReceiverEmail());
                    } else {
                        $output->writeln('Newsletter-Versand fehlgeschlagen für ' . $receiver->getReceiverEmail());
                    }
                }
                $this->em->persist($receiver);
            }
        }
        $this->em->flush();

        return Command::SUCCESS;
    }

}
