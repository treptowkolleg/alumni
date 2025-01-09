<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(
        mapping: 'userprofiles', # We will remember this value. It will serve as the identifier for the section in the configuration.
        fileNameProperty: 'image.name',
        size: 'image.size'
    )]
    private ?File $imageFile = null;
    #[ORM\Embedded(class: EmbeddedFile::class)]
    private ?EmbeddedFile $image = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    public ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $inYear = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $outYear = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currentProfession = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currentCompany = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $studium = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $university = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $portfolioLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $networkState = null;

    #[ORM\Column(nullable: true)]
    private ?array $hobbies = null;

    #[ORM\Column(nullable: true)]
    private ?array $favoriteSchoolSubjects = null;

    #[ORM\Column(nullable: true)]
    private ?array $interests = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $about = null;

    #[ORM\Column(nullable: true)]
    private ?array $performanceCourse = null;

    #[ORM\Column(nullable: true)]
    private ?string $examType = null;

    #[ORM\ManyToOne(inversedBy: 'userProfiles')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?array $tags = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayName = null;

    public function __construct()
    {
        $this->image = new EmbeddedFile();
    }

    public function __toString(): string
    {
        return $this->user;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getInYear(): ?string
    {
        return $this->inYear;
    }

    public function setInYear(?string $inYear): static
    {
        $this->inYear = $inYear;

        return $this;
    }

    public function getOutYear(): ?string
    {
        return $this->outYear;
    }

    public function setOutYear(?string $outYear): static
    {
        $this->outYear = $outYear;

        return $this;
    }

    public function getCurrentProfession(): ?string
    {
        return $this->currentProfession;
    }

    public function setCurrentProfession(?string $currentProfession): static
    {
        $this->currentProfession = $currentProfession;

        return $this;
    }

    public function getCurrentCompany(): ?string
    {
        return $this->currentCompany;
    }

    public function setCurrentCompany(?string $currentCompany): static
    {
        $this->currentCompany = $currentCompany;

        return $this;
    }

    public function getStudium(): ?string
    {
        return $this->studium;
    }

    public function setStudium(?string $studium): static
    {
        $this->studium = $studium;

        return $this;
    }

    public function getUniversity(): ?string
    {
        return $this->university;
    }

    public function setUniversity(?string $university): static
    {
        $this->university = $university;

        return $this;
    }

    public function getPortfolioLink(): ?string
    {
        return $this->portfolioLink;
    }

    public function setPortfolioLink(?string $portfolioLink): static
    {
        $this->portfolioLink = $portfolioLink;

        return $this;
    }

    public function getNetworkState(): ?string
    {
        return $this->networkState;
    }

    public function setNetworkState(?string $networkState): static
    {
        $this->networkState = $networkState;

        return $this;
    }

    public function getHobbies(): ?array
    {
        return $this->hobbies;
    }

    public function setHobbies(?array $hobbies): static
    {
        $this->hobbies = $hobbies;

        return $this;
    }

    public function getFavoriteSchoolSubjects(): ?array
    {
        return $this->favoriteSchoolSubjects;
    }

    public function setFavoriteSchoolSubjects(?array $favoriteSchoolSubjects): static
    {
        $this->favoriteSchoolSubjects = $favoriteSchoolSubjects;

        return $this;
    }

    public function getInterests(): ?array
    {
        return $this->interests;
    }

    public function setInterests(?array $interests): static
    {
        $this->interests = $interests;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getPerformanceCourse(): ?array
    {
        return $this->performanceCourse;
    }

    public function setPerformanceCourse(?array $performanceCourse): static
    {
        $this->performanceCourse = $performanceCourse;

        return $this;
    }

    public function getExamType(): ?string
    {
        return $this->examType;
    }

    public function setExamType(?string $examType): static
    {
        $this->examType = $examType;

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

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): static
    {
        $tagList = [];
        foreach ($tags as $tag) {
            $tagList = array_merge($tagList,array_values(explode(",", $tag)));
        }
        $this->tags = array_unique($tagList);

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }
}
