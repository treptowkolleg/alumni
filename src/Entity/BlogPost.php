<?php

namespace App\Entity;

use App\Repository\BlogPostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Symfony\Component\HttpFoundation\File\File;
use Gedmo\Mapping\Annotation as Gedmo;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
class BlogPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['title'])]
    private $slug;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'blogPosts')]
    private ?BlogType $type = null;

    #[Vich\UploadableField(
        mapping: 'blogposts', # We will remember this value. It will serve as the identifier for the section in the configuration.
        fileNameProperty: 'image.name',
        size: 'image.size'
    )]
    private ?File $imageFile = null;
    #[ORM\Embedded(class: EmbeddedFile::class)]
    private ?EmbeddedFile $image = null;

    #[Vich\UploadableField(
        mapping: 'podcasts', # We will remember this value. It will serve as the identifier for the section in the configuration.
        fileNameProperty: 'audio.name',
        size: 'audio.size'
    )]
    private ?File $audioFile = null;
    #[ORM\Embedded(class: EmbeddedFile::class)]
    private ?EmbeddedFile $audio = null;

    #[ORM\Column]
    private ?bool $isPublished = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    public ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isLeadPost = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;

    #[ORM\ManyToOne(inversedBy: 'blogPosts')]
    private ?User $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $blogPostImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $blogPostAudio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageCite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageCityUrl = null;

    public function __construct()
    {
        $this->image = new EmbeddedFile();
        $this->audio = new EmbeddedFile();
    }

    public function __toString(): string
    {
        return (string) $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getType(): ?BlogType
    {
        return $this->type;
    }

    public function setType(?BlogType $type): static
    {
        $this->type = $type;

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
            $this->updatedAt = new \DateTimeImmutable();
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

    public function getAudioFile(): ?File
    {
        return $this->audioFile;
    }

    public function setAudioFile(?File $audioFile): static
    {
        $this->audioFile = $audioFile;
        if (null !== $audioFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getAudio(): ?EmbeddedFile
    {
        return $this->audio;
    }

    public function setAudo(?EmbeddedFile $audio): static
    {
        $this->audio = $audio;
        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isLeadPost(): ?bool
    {
        return $this->isLeadPost;
    }

    public function setIsLeadPost(?bool $isLeadPost): static
    {
        $this->isLeadPost = $isLeadPost;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }


    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getBlogPostImage(): ?string
    {
        return $this->blogPostImage;
    }

    public function setBlogPostImage(?string $blogPostImage): static
    {
        $this->blogPostImage = $blogPostImage;

        return $this;
    }

    public function getBlogPostImageUrl(): ?string
    {
        if (!$this->blogPostImage) {
            return null;
        }
        if (strpos($this->blogPostImage, '/') !== false) {
            return $this->blogPostImage;
        }
        return sprintf('/images/blogpost/%s', $this->blogPostImage);
    }

    public function getBlogPostAudio(): ?string
    {
        return $this->blogPostAudio;
    }

    public function setBlogPostAudio(?string $blogPostAudio): static
    {
        $this->blogPostAudio = $blogPostAudio;

        return $this;
    }

    public function getBlogPostAudioUrl(): ?string
    {
        if (!$this->blogPostAudio) {
            return null;
        }
        if (strpos($this->blogPostAudio, '/') !== false) {
            return $this->blogPostAudio;
        }
        return sprintf('/audio/podcast/%s', $this->blogPostAudio);
    }

    public function getImageCite(): ?string
    {
        return $this->imageCite;
    }

    public function setImageCite(?string $imageCite): static
    {
        $this->imageCite = $imageCite;

        return $this;
    }

    public function getImageCityUrl(): ?string
    {
        return $this->imageCityUrl;
    }

    public function setImageCityUrl(?string $imageCityUrl): static
    {
        $this->imageCityUrl = $imageCityUrl;

        return $this;
    }

}
