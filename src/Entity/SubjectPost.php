<?php

namespace App\Entity;

use App\Repository\SubjectPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: SubjectPostRepository::class)]
class SubjectPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subjectPosts')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $subjectPosts;

    #[ORM\ManyToOne(inversedBy: 'subjectPosts')]
    private ?User $owner = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $created = null;

    #[ORM\ManyToOne(inversedBy: 'subjectPosts')]
    private ?GroupSubject $subject = null;

    public function __construct()
    {
        $this->subjectPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubjectPosts(): Collection
    {
        return $this->subjectPosts;
    }

    public function addSubjectPost(self $subjectPost): static
    {
        if (!$this->subjectPosts->contains($subjectPost)) {
            $this->subjectPosts->add($subjectPost);
            $subjectPost->setParent($this);
        }

        return $this;
    }

    public function removeSubjectPost(self $subjectPost): static
    {
        if ($this->subjectPosts->removeElement($subjectPost)) {
            // set the owning side to null (unless already changed)
            if ($subjectPost->getParent() === $this) {
                $subjectPost->setParent(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function getSubject(): ?GroupSubject
    {
        return $this->subject;
    }

    public function setSubject(?GroupSubject $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

}
