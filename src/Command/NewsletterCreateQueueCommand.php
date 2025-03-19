<?php

namespace App\Command;

use App\Entity\NewsletterQueue;
use App\Entity\NewsletterTemplate;
use App\Repository\NewsletterTemplateRepository;
use DatePeriod;
use Doctrine\ORM\EntityManagerInterface;
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
    public function __construct(EntityManagerInterface $entityManager, NewsletterTemplateRepository $templateRepository)
    {
        $this->em = $entityManager;
        $this->templateRepository = $templateRepository;
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
        $today->setTime(18,0,0);

        foreach ($templates as $template) {
            $output->writeln("Template: " . $template->getTitle());

            if ($this->isSendDateToday($template->getStartDate(), $template->getPeriod(), $template->getPeriodUnit(), $output)) {
                $output->writeln("Sende Newsletter: " . $template->getId());
                $schools = $template->getSchool();
                foreach ($schools as $school) {
                    $output->writeln("Schule: " . $school->getTitle());

                    foreach($school->getNewsletters() as $newsletter) {
                        $output->writeln("Sende Newsletter: " . $newsletter->getEmail());

                        $queue = new NewsletterQueue();
                        $queue->setTemplate($template);
                        $queue->setReceiverEmail($newsletter->getEmail());
                        $queue->setSendDate($today);
                        $queue->setSend(false);
                        $queue->setUserCount($school->getUsers()->count());
                        $this->em->persist($queue);
                    }

                }
            }
        }
        $this->em->flush();

        return Command::SUCCESS;
    }

    public function isSendDateToday(\DateTimeInterface $startDate, int $period, string $periodUnit, OutputInterface $output): bool
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
            $output->writeln("Datum: " . $date->format('Y-m-d'));
            if ($date->format('Y-m-d') === (new \DateTime())->format('Y-m-d')) {
                return true;
            }
        }

        return false;
    }
}
