<?php

namespace App\Entity;

use App\Repository\GroupSubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: GroupSubjectRepository::class)]
class GroupSubject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private $slug;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(
        mapping: 'group_image',
        fileNameProperty: 'image.name',
        size: 'image.size'
    )]
    private ?File $imageFile = null;
    #[ORM\Embedded(class: EmbeddedFile::class)]
    private ?EmbeddedFile $image = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $created = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    private ?\DateTimeImmutable $updated = null;

    #[ORM\ManyToOne(inversedBy: 'groupSubjects')]
    private ?User $owner = null;

    #[ORM\Column]
    private ?bool $private = null;

    #[ORM\ManyToOne(inversedBy: 'groupSubjects')]
    private ?Hobby $hobby = null;

    #[ORM\ManyToOne(inversedBy: 'groupSubjects')]
    private ?Interest $interest = null;

    /**
     * @var Collection<int, SubjectPost>
     */
    #[ORM\OneToMany(targetEntity: SubjectPost::class, mappedBy: 'subject')]
    private Collection $subjectPosts;

    public function __construct()
    {
        $this->subjectPosts = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
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

    public function getSlug(): string
    {
        return $this->slug;
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

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): static
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getImage(): ?EmbeddedFile
    {
        return $this->image;
    }

    public function setImage(?EmbeddedFile $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function getUpdated(): ?\DateTimeImmutable
    {
        return $this->updated;
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

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): static
    {
        $this->private = $private;

        return $this;
    }

    public function getHobby(): ?Hobby
    {
        return $this->hobby;
    }

    public function setHobby(?Hobby $hobby): static
    {
        $this->hobby = $hobby;

        return $this;
    }

    public function getInterest(): ?Interest
    {
        return $this->interest;
    }

    public function setInterest(?Interest $interest): static
    {
        $this->interest = $interest;

        return $this;
    }

    /**
     * @return Collection<int, SubjectPost>
     */
    public function getSubjectPosts(): Collection
    {
        return $this->subjectPosts;
    }

    public function addSubjectPost(SubjectPost $subjectPost): static
    {
        if (!$this->subjectPosts->contains($subjectPost)) {
            $this->subjectPosts->add($subjectPost);
            $subjectPost->setSubject($this);
        }

        return $this;
    }

    public function removeSubjectPost(SubjectPost $subjectPost): static
    {
        if ($this->subjectPosts->removeElement($subjectPost)) {
            // set the owning side to null (unless already changed)
            if ($subjectPost->getSubject() === $this) {
                $subjectPost->setSubject(null);
            }
        }

        return $this;
    }

}
