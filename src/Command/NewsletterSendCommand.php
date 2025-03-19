<?php

namespace App\Command;

use App\Entity\NewsletterQueue;
use App\Entity\NewsletterTemplate;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Repository\NewsletterTemplateRepository;
use App\Repository\UserRepository;
use DatePeriod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
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
    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->em = $entityManager;
        $this->mailer = $mailer;
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

        $queue = $this->em->getRepository(NewsletterQueue::class)->findBy(['send' => false]);

        foreach ($queue as $receiver) {

            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $receiver->getReceiverEmail()]);

            $email = (new TemplatedEmail())
                ->from(new Address('service@alumni-portal.org', 'Alumni-Portal'))
                ->to((string) $receiver->getReceiverEmail())
                ->subject($receiver->getTemplate()->getTitle())
                ->htmlTemplate('newsletter/default.html.twig')
                ->context([
                    'user' => $user,
                    'config' => $receiver->getTemplate()
                ])
            ;

            $this->mailer->send($email);

            $receiver->setSend(true);
            $this->em->persist($receiver);
        }
        $this->em->flush();

        return Command::SUCCESS;
    }

}
