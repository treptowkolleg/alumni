<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['firstname', 'lastname'])]
    private $slug;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?School $school = null;

    #[ORM\Column(length: 255)]
    private ?string $userType = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasSchool = null;

    #[Vich\UploadableField(
        mapping: 'userimage', # We will remember this value. It will serve as the identifier for the section in the configuration.
        fileNameProperty: 'image.name',
        size: 'image.size'
    )]
    private ?File $imageFile = null;
    #[ORM\Embedded(class: EmbeddedFile::class)]
    private ?EmbeddedFile $image = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Gedmo\Timestampable]
    public ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, BlogPost>
     */
    #[ORM\OneToMany(targetEntity: BlogPost::class, mappedBy: 'author')]
    private Collection $blogPosts;

    /**
     * @var Collection<int, UserProfile>
     */
    #[ORM\OneToMany(targetEntity: UserProfile::class, mappedBy: 'user')]
    private Collection $userProfiles;

    /**
     * @var Collection<int, ChatMessage>
     */
    #[ORM\OneToMany(targetEntity: ChatMessage::class, mappedBy: 'sender')]
    private Collection $chatMessages;

    /**
     * @var Collection<int, Chat>
     */
    #[ORM\OneToMany(targetEntity: Chat::class, mappedBy: 'owner')]
    private Collection $chats;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'user')]
    private Collection $events;

    /**
     * @var Collection<int, Newsletter>
     */
    #[ORM\OneToMany(targetEntity: Newsletter::class, mappedBy: 'user')]
    private Collection $newsletters;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstnameSoundEx = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastnameSoundEx = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'followers')]
    private Collection $followedEvents;

    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
        $this->userProfiles = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->newsletters = new ArrayCollection();
        $this->followedEvents = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getUserIdentifier();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getFullname(): string
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getShortName(): string
    {
        return strtoupper(substr($this->getFirstname(),0,1) . substr($this->getLastname(),0,2));
    }

    public function getAvatar(): string
    {
        return strtoupper(substr($this->getFirstname(),0,1) . substr($this->getLastname(),0,1));
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): static
    {
        $this->school = $school;

        return $this;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): static
    {
        $this->userType = $userType;

        return $this;
    }

    public function hasSchool(): ?bool
    {
        return $this->hasSchool;
    }

    public function setHasSchool(?bool $hasSchool): static
    {
        $this->hasSchool = $hasSchool;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }

    public function addBlogPost(BlogPost $blogPost): static
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts->add($blogPost);
            $blogPost->setAuthor($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogPost $blogPost): static
    {
        if ($this->blogPosts->removeElement($blogPost)) {
            // set the owning side to null (unless already changed)
            if ($blogPost->getAuthor() === $this) {
                $blogPost->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserProfile>
     */
    public function getUserProfiles(): Collection
    {
        return $this->userProfiles;
    }

    public function addUserProfile(UserProfile $userProfile): static
    {
        if (!$this->userProfiles->contains($userProfile)) {
            $this->userProfiles->add($userProfile);
            $userProfile->setUser($this);
        }

        return $this;
    }

    public function removeUserProfile(UserProfile $userProfile): static
    {
        if ($this->userProfiles->removeElement($userProfile)) {
            // set the owning side to null (unless already changed)
            if ($userProfile->getUser() === $this) {
                $userProfile->setUser(null);
            }
        }

        return $this;
    }

    public function serialize(): string
    {
        // Only serialize properties that should be persisted or transferred
        return serialize([
            $this->id,
            $this->email,
            $this->roles,
            $this->password,
            $this->firstname,
            $this->lastname,
            $this->isVerified,
            $this->school,
            $this->userType,
            $this->hasSchool,
            $this->image, // Serialize only the EmbeddedFile property, not the File property
            $this->updatedAt,
        ]);
    }

    public function unserialize(string $data): void
    {
        [
            $this->email,
            $this->roles,
            $this->password,
            $this->firstname,
            $this->lastname,
            $this->isVerified,
            $this->school,
            $this->userType,
            $this->hasSchool,
            $this->image,
            $this->updatedAt,
        ] = unserialize($data, ['allowed_classes' => true]);
    }

    public function __serialize(): array
    {
        // Similar to serialize, but returns an array
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'password' => $this->password,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'isVerified' => $this->isVerified,
            'school' => $this->school,
            'userType' => $this->userType,
            'hasSchool' => $this->hasSchool,
            'image' => $this->image,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public function __unserialize(array $data): void
    {
        // Restore the properties from the array
        $this->id = $data['id'];
        $this->email = $data['email'];
        $this->roles = $data['roles'];
        $this->password = $data['password'];
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->isVerified = $data['isVerified'];
        $this->school = $data['school'];
        $this->userType = $data['userType'];
        $this->hasSchool = $data['hasSchool'];
        $this->image = $data['image'];
        $this->updatedAt = $data['updatedAt'];
    }

    /**
     * @return Collection<int, ChatMessage>
     */
    public function getChatMessages(): Collection
    {
        return $this->chatMessages;
    }

    public function addChatMessage(ChatMessage $chatMessage): static
    {
        if (!$this->chatMessages->contains($chatMessage)) {
            $this->chatMessages->add($chatMessage);
            $chatMessage->setSender($this);
        }

        return $this;
    }

    public function removeChatMessage(ChatMessage $chatMessage): static
    {
        if ($this->chatMessages->removeElement($chatMessage)) {
            // set the owning side to null (unless already changed)
            if ($chatMessage->getSender() === $this) {
                $chatMessage->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): static
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->setOwner($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): static
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getOwner() === $this) {
                $chat->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setUser($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getUser() === $this) {
                $event->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Newsletter>
     */
    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(Newsletter $newsletter): static
    {
        if (!$this->newsletters->contains($newsletter)) {
            $this->newsletters->add($newsletter);
            $newsletter->setUser($this);
        }

        return $this;
    }

    public function removeNewsletter(Newsletter $newsletter): static
    {
        if ($this->newsletters->removeElement($newsletter)) {
            // set the owning side to null (unless already changed)
            if ($newsletter->getUser() === $this) {
                $newsletter->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstnameSoundEx(): ?string
    {
        return $this->firstnameSoundEx;
    }

    public function setFirstnameSoundEx(?string $firstnameSoundEx): static
    {
        $this->firstnameSoundEx = $firstnameSoundEx;

        return $this;
    }

    public function getLastnameSoundEx(): ?string
    {
        return $this->lastnameSoundEx;
    }

    public function setLastnameSoundEx(?string $lastnameSoundEx): static
    {
        $this->lastnameSoundEx = $lastnameSoundEx;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getFollowedEvents(): Collection
    {
        return $this->followedEvents;
    }

    public function addFollowedEvent(Event $followedEvent): static
    {
        if (!$this->followedEvents->contains($followedEvent)) {
            $this->followedEvents->add($followedEvent);
            $followedEvent->addFollower($this);
        }

        return $this;
    }

    public function removeFollowedEvent(Event $followedEvent): static
    {
        if ($this->followedEvents->removeElement($followedEvent)) {
            $followedEvent->removeFollower($this);
        }

        return $this;
    }
}
