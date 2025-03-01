<?php

namespace App\Entity;

use App\Repository\PersonOfferRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: PersonOfferRepository::class)]
class PersonOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'personOffers')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private $slug;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $created = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    public ?\DateTimeImmutable $updated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $county = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'personOffers')]
    private ?OfferType $type = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function setDescription(?string $description): static
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

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function getUpdated(): ?\DateTimeImmutable
    {
        return $this->updated;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function setCounty(?string $county): static
    {
        $this->county = $county;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getType(): ?OfferType
    {
        return $this->type;
    }

    public function setType(?OfferType $type): static
    {
        $this->type = $type;

        return $this;
    }

}
