<?php

namespace App\Entity;

use App\Repository\OfferTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: OfferTypeRepository::class)]
class OfferType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private $slug;

    /**
     * @var Collection<int, PersonOffer>
     */
    #[ORM\OneToMany(targetEntity: PersonOffer::class, mappedBy: 'type')]
    private Collection $personOffers;

    public function __construct()
    {
        $this->personOffers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
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

    /**
     * @return mixed
     */
    public function getSlug(): mixed
    {
        return $this->slug;
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
            $personOffer->setType($this);
        }

        return $this;
    }

    public function removePersonOffer(PersonOffer $personOffer): static
    {
        if ($this->personOffers->removeElement($personOffer)) {
            // set the owning side to null (unless already changed)
            if ($personOffer->getType() === $this) {
                $personOffer->setType(null);
            }
        }

        return $this;
    }

}
