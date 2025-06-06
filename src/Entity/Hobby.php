<?php

namespace App\Entity;

use App\Repository\HobbyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: HobbyRepository::class)]
class Hobby
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $label = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['label'])]
    private $slug;

    #[ORM\ManyToOne(inversedBy: 'hobbies')]
    private ?HobbyCategory $category = null;

    /**
     * @var Collection<int, UserProfile>
     */
    #[ORM\ManyToMany(targetEntity: UserProfile::class, mappedBy: 'userHobbies')]
    private Collection $userProfiles;

    /**
     * @var Collection<int, PersonOffer>
     */
    #[ORM\ManyToMany(targetEntity: PersonOffer::class, mappedBy: 'hobbies')]
    private Collection $personOffers;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'hobbies')]
    private Collection $events;

    /**
     * @var Collection<int, GroupSubject>
     */
    #[ORM\OneToMany(targetEntity: GroupSubject::class, mappedBy: 'hobby')]
    private Collection $groupSubjects;

    public function __construct()
    {
        $this->userProfiles = new ArrayCollection();
        $this->personOffers = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->groupSubjects = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug(): mixed
    {
        return $this->slug;
    }

    public function getCategory(): ?HobbyCategory
    {
        return $this->category;
    }

    public function setCategory(?HobbyCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, UserProfile>
     */
    public function getUserProfiles(): Collection
    {
        return $this->userProfiles;
    }

    public function addUserProfile(UserProfile $userProfile): static
    {
        if (!$this->userProfiles->contains($userProfile)) {
            $this->userProfiles->add($userProfile);
            $userProfile->addUserHobby($this);
        }

        return $this;
    }

    public function removeUserProfile(UserProfile $userProfile): static
    {
        if ($this->userProfiles->removeElement($userProfile)) {
            $userProfile->removeUserHobby($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PersonOffer>
     */
    public function getPersonOffers(): Collection
    {
        return $this->personOffers;
    }

    public function addPersonOffer(PersonOffer $personOffer): static
    {
        if (!$this->personOffers->contains($personOffer)) {
            $this->personOffers->add($personOffer);
            $personOffer->addHobby($this);
        }

        return $this;
    }

    public function removePersonOffer(PersonOffer $personOffer): static
    {
        if ($this->personOffers->removeElement($personOffer)) {
            $personOffer->removeHobby($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addHobby($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeHobby($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupSubject>
     */
    public function getGroupSubjects(): Collection
    {
        return $this->groupSubjects;
    }

    public function addGroupSubject(GroupSubject $groupSubject): static
    {
        if (!$this->groupSubjects->contains($groupSubject)) {
            $this->groupSubjects->add($groupSubject);
            $groupSubject->setHobby($this);
        }

        return $this;
    }

    public function removeGroupSubject(GroupSubject $groupSubject): static
    {
        if ($this->groupSubjects->removeElement($groupSubject)) {
            // set the owning side to null (unless already changed)
            if ($groupSubject->getHobby() === $this) {
                $groupSubject->setHobby(null);
            }
        }

        return $this;
    }
}
