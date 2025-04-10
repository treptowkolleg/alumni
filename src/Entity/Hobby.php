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

    public function __construct()
    {
        $this->userProfiles = new ArrayCollection();
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
}
