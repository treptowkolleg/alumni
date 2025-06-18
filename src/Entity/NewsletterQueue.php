<?php

namespace App\Entity;

use App\Repository\NewsletterQueueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterQueueRepository::class)]
class NewsletterQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $receiverEmail = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $sendDate = null;

    #[ORM\Column]
    private ?bool $send = null;

    #[ORM\ManyToOne(inversedBy: 'newsletterQueues')]
    private ?NewsletterTemplate $template = null;

    #[ORM\Column]
    private ?int $userCount = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $token = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceiverEmail(): ?string
    {
        return $this->receiverEmail;
    }

    public function setReceiverEmail(string $receiverEmail): static
    {
        $this->receiverEmail = $receiverEmail;

        return $this;
    }

    public function getSendDate(): ?\DateTimeInterface
    {
        return $this->sendDate;
    }

    public function setSendDate(\DateTimeInterface $sendDate): static
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    public function isSend(): ?bool
    {
        return $this->send;
    }

    public function setSend(bool $send): static
    {
        $this->send = $send;

        return $this;
    }

    public function getTemplate(): ?NewsletterTemplate
    {
        return $this->template;
    }

    public function setTemplate(?NewsletterTemplate $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getUserCount(): ?int
    {
        return $this->userCount;
    }

    public function setUserCount(int $userCount): static
    {
        $this->userCount = $userCount;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }
}
