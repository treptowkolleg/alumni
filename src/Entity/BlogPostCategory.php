<?php

namespace App\Entity;

use App\Repository\BlogPostCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: BlogPostCategoryRepository::class)]
class BlogPostCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Gedmo\Slug(fields: ['label'])]
    private $slug;

    /**
     * @var Collection<int, BlogPost>
     */
    #[ORM\OneToMany(targetEntity: BlogPost::class, mappedBy: 'category')]
    private Collection $blogPosts;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'blogPostCategories')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $blogPostCategories;

    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
        $this->blogPostCategories = new ArrayCollection();
    }

    public function __toString(): string
    {
        $label = $this->label;
        if($this->parent){
            $label .= " ({$this->parent->getLabel()})";
        }
        return $label;
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

    public function getSlug(): string
    {
        return $this->slug;
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
            $blogPost->setCategory($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogPost $blogPost): static
    {
        if ($this->blogPosts->removeElement($blogPost)) {
            // set the owning side to null (unless already changed)
            if ($blogPost->getCategory() === $this) {
                $blogPost->setCategory(null);
            }
        }

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
    public function getBlogPostCategories(): Collection
    {
        return $this->blogPostCategories;
    }

    public function addBlogPostCategory(self $blogPostCategory): static
    {
        if (!$this->blogPostCategories->contains($blogPostCategory)) {
            $this->blogPostCategories->add($blogPostCategory);
            $blogPostCategory->setParent($this);
        }

        return $this;
    }

    public function removeBlogPostCategory(self $blogPostCategory): static
    {
        if ($this->blogPostCategories->removeElement($blogPostCategory)) {
            // set the owning side to null (unless already changed)
            if ($blogPostCategory->getParent() === $this) {
                $blogPostCategory->setParent(null);
            }
        }

        return $this;
    }
}
