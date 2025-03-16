<?php

namespace App\Entity;

use App\Repository\NewsletterTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterTemplateRepository::class)]
class NewsletterTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column]
    private ?int $period = null;

    #[ORM\Column(length: 255)]
    private ?string $periodUnit = null;

    /**
     * @var Collection<int, School>
     */
    #[ORM\ManyToMany(targetEntity: School::class, inversedBy: 'newsletterTemplates')]
    private Collection $school;

    #[ORM\Column]
    private ?bool $useAllReceivers = null;

    #[ORM\Column]
    private ?bool $showEvents = null;

    #[ORM\Column]
    private ?bool $showRecentPosts = null;

    #[ORM\Column]
    private ?bool $showOffers = null;

    #[ORM\Column]
    private ?bool $showRecentNews = null;

    #[ORM\Column]
    private ?bool $showRecentPins = null;

    #[ORM\ManyToOne(inversedBy: 'newsletterTemplates')]
    private ?User $user = null;

    public function __construct()
    {
        $this->school = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getPeriodUnit(): ?string
    {
        return $this->periodUnit;
    }

    public function setPeriodUnit(string $periodUnit): static
    {
        $this->periodUnit = $periodUnit;

        return $this;
    }

    /**
     * @return Collection<int, School>
     */
    public function getSchool(): Collection
    {
        return $this->school;
    }

    public function addSchool(School $school): static
    {
        if (!$this->school->contains($school)) {
            $this->school->add($school);
        }

        return $this;
    }

    public function removeSchool(School $school): static
    {
        $this->school->removeElement($school);

        return $this;
    }

    public function isUseAllReceivers(): ?bool
    {
        return $this->useAllReceivers;
    }

    public function setUseAllReceivers(bool $useAllReceivers): static
    {
        $this->useAllReceivers = $useAllReceivers;

        return $this;
    }

    public function isShowEvents(): ?bool
    {
        return $this->showEvents;
    }

    public function setShowEvents(bool $showEvents): static
    {
        $this->showEvents = $showEvents;

        return $this;
    }

    public function isShowRecentPosts(): ?bool
    {
        return $this->showRecentPosts;
    }

    public function setShowRecentPosts(bool $showRecentPosts): static
    {
        $this->showRecentPosts = $showRecentPosts;

        return $this;
    }

    public function isShowOffers(): ?bool
    {
        return $this->showOffers;
    }

    public function setShowOffers(bool $showOffers): static
    {
        $this->showOffers = $showOffers;

        return $this;
    }

    public function isShowRecentNews(): ?bool
    {
        return $this->showRecentNews;
    }

    public function setShowRecentNews(bool $showRecentNews): static
    {
        $this->showRecentNews = $showRecentNews;

        return $this;
    }

    public function isShowRecentPins(): ?bool
    {
        return $this->showRecentPins;
    }

    public function setShowRecentPins(bool $showRecentPins): static
    {
        $this->showRecentPins = $showRecentPins;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
