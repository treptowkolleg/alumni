<?php

namespace App\Entity;

use App\Repository\UserImageUploadRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Symfony\Component\HttpFoundation\File\File;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: UserImageUploadRepository::class)]
class UserImageUpload
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userImageUploads')]
    private ?User $user = null;

    #[Vich\UploadableField(
        mapping: 'user_uploads', # We will remember this value. It will serve as the identifier for the section in the configuration.
        fileNameProperty: 'image.name',
        size: 'image.size'
    )]
    #[Assert\Image(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
        maxWidth: 2000,
        maxHeight: 2000,
        mimeTypesMessage: 'Bitte ein gültiges Bildformat hochladen (JPEG, PNG, WEBP).',
        maxWidthMessage: 'Das Bild darf maximal 2000 Pixel breit sein.',
        maxHeightMessage: 'Das Bild darf maximal 2000 Pixel hoch sein.'
    )]
    private ?File $imageFile = null;
    #[ORM\Embedded(class: EmbeddedFile::class)]
    private ?EmbeddedFile $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userImage = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $created = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    public ?\DateTimeImmutable $updated = null;

    public function __construct()
    {
        $this->image = new EmbeddedFile();
    }

    public function __toString(): string
    {
        return $this->image->getName() ?: "";
    }

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

    public function getUserImage(): ?string
    {
        return $this->userImage;
    }

    public function setUserImage(?string $userImage): static
    {
        $this->userImage = $userImage;

        return $this;
    }

    public function getUserImageUrl(): ?string
    {
        if (!$this->userImage) {
            return null;
        }
        if (strpos($this->userImage, '/') !== false) {
            return $this->userImage;
        }
        return sprintf('/images/user_uploads/%s', $this->userImage);
    }

    public function getCreated(): ?\DateTimeImmutable
    {
        return $this->created;
    }

    public function setCreated(?\DateTimeImmutable $created): void
    {
        $this->created = $created;
    }

    public function getUpdated(): ?\DateTimeImmutable
    {
        return $this->updated;
    }

}
