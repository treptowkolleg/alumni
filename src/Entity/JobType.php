<?php

namespace App\Entity;

use App\Repository\JobTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobTypeRepository::class)]
class JobType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 100)]
    private ?string $label = null;

    /**
     * @var Collection<int, PersonOffer>
     */
    #[ORM\OneToMany(targetEntity: PersonOffer::class, mappedBy: 'jobType')]
    private Collection $personOffers;

    public function __construct()
    {
        $this->personOffers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
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
            $personOffer->setJobType($this);
        }

        return $this;
    }

    public function removePersonOffer(PersonOffer $personOffer): static
    {
        if ($this->personOffers->removeElement($personOffer)) {
            // set the owning side to null (unless already changed)
            if ($personOffer->getJobType() === $this) {
                $personOffer->setJobType(null);
            }
        }

        return $this;
    }
}
