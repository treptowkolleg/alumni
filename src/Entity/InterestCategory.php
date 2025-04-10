<?php

namespace App\Entity;

use App\Repository\InterestCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: InterestCategoryRepository::class)]
class InterestCategory
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

    /**
     * @var Collection<int, Interest>
     */
    #[ORM\OneToMany(targetEntity: Interest::class, mappedBy: 'category')]
    private Collection $interests;

    public function __construct()
    {
        $this->interests = new ArrayCollection();
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

    /**
     * @return Collection<int, Interest>
     */
    public function getInterests(): Collection
    {
        return $this->interests;
    }

    public function addInterest(Interest $interest): static
    {
        if (!$this->interests->contains($interest)) {
            $this->interests->add($interest);
            $interest->setCategory($this);
        }

        return $this;
    }

    public function removeInterest(Interest $interest): static
    {
        if ($this->interests->removeElement($interest)) {
            // set the owning side to null (unless already changed)
            if ($interest->getCategory() === $this) {
                $interest->setCategory(null);
            }
        }

        return $this;
    }
}
