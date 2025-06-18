<?php

namespace App\Command;

use App\Entity\NewsletterQueue;
use App\Entity\NewsletterTemplate;
use App\Repository\NewsletterRepository;
use App\Repository\NewsletterTemplateRepository;
use App\Service\TokenService;
use DatePeriod;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Newsletter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:newsletter-create-queue',
    description: 'Add a short description for your command',
)]
class NewsletterCreateQueueCommand extends Command
{
    private EntityManagerInterface $em;
    private NewsletterTemplateRepository $templateRepository;
    private NewsletterRepository $newsletterRepository;

    private TokenService $tokenService;
    public function __construct(EntityManagerInterface $entityManager, NewsletterTemplateRepository $templateRepository, NewsletterRepository $newsletterRepository, TokenService $tokenService)
    {
        $this->em = $entityManager;
        $this->templateRepository = $templateRepository;
        $this->newsletterRepository = $newsletterRepository;
        $this->tokenService = $tokenService;
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

        $templates = $this->templateRepository->findAll();
        $today = new \DateTimeImmutable();

        foreach ($templates as $template) {
            if ($this->isSendDateToday($template->getStartDate(), $template->getPeriod(), $template->getPeriodUnit())) {
                $sendHour = $template->getStartDate()->format("H");
                $today = $today->setTime($sendHour,0,0);
                if (!$template->isUseAllReceivers()) {
                    $schools = $template->getSchool();
                    foreach ($schools as $school) {
                        foreach ($school->getNewsletters() as $newsletter) {
                            if (!$this->em->getRepository(NewsletterQueue::class)->findOneBy(['receiverEmail' => $newsletter->getEmail(), 'send' => false])) {
                                $queue = new NewsletterQueue();
                                $queue->setTemplate($template);
                                $queue->setReceiverEmail($newsletter->getEmail());
                                $queue->setSendDate($today);
                                $queue->setToken($this->tokenService->generateUnsubscribeToken($newsletter->getEmail()));
                                $queue->setSend(false);
                                $queue->setUserCount($school->getUsers()->count());
                                $this->em->persist($queue);
                            }
                        }
                    }
                } else {
                    $receivers = $this->em->getRepository(Newsletter::class)->findBy(['user' => null]);
                    $count = count($receivers);
                    $output->writeln("Es gibt $count Empfänger ohne Konto.");
                    foreach ($receivers as $newsletter) {
                        if (!$this->em->getRepository(NewsletterQueue::class)->findOneBy(['receiverEmail' => $newsletter->getEmail(), 'send' => false])) {
                            $queue = new NewsletterQueue();
                            $queue->setTemplate($template);
                            $queue->setReceiverEmail($newsletter->getEmail());
                            $queue->setSendDate($today);
                            $queue->setToken($this->tokenService->generateUnsubscribeToken($newsletter->getEmail()));
                            $queue->setSend(false);
                            $queue->setUserCount(0);
                            $this->em->persist($queue);
                        }
                    }

                }
            }


        }
        $this->em->flush();

        return Command::SUCCESS;
    }

    public function isSendDateToday(\DateTimeInterface $startDate, int $period, string $periodUnit): bool
    {
        $intervalSpec = match ($periodUnit) {
            'd' => "P{$period}D",
            'w' => "P{$period}W",
            'm' => "P{$period}M",
            'y' => "P{$period}Y",
            default => throw new \InvalidArgumentException("Ungültige period_unit: $periodUnit"),
        };

        $interval = new \DateInterval($intervalSpec);
        $today = new \DateTimeImmutable("tomorrow");

        $period = new \DatePeriod($startDate, $interval, $today);

        foreach ($period as $date) {
            if ($date->format('Y-m-d') === (new \DateTime())->format('Y-m-d')) {
                return true;
            }
        }

        return false;
    }
}
