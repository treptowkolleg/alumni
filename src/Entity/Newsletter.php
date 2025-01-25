<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
class Newsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'newsletters')]
    private ?User $user = null;

    /**
     * @var Collection<int, School>
     */
    #[ORM\ManyToMany(targetEntity: School::class, inversedBy: 'newsletters')]
    private Collection $school;

    public function __construct()
    {
        $this->school = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

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
}
