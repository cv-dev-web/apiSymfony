<?php

namespace App\Entity;

use App\Entity\Content;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ResourceRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['listResourceFull']],
    collectionOperations: [ 
        'get',
        'post' => [ 
            'normalization_context' => ['groups' => ['write:itemResource']]
        ]
    ]
    
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [  'title' => 'ipartial']
    
)]
class Resource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['listResourceFull'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['listResourceFull','write:itemResource'])]
    private $title;

    #[ORM\Column(type: 'boolean')]
    private $visibility;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['listResourceFull'])]
    private $creationDate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['listResourceFull','write:itemResource'])]
    #[ApiSubresource]
    private $user;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['listResourceFull','write:itemResource'])]
    #[ApiSubresource]
    private $category;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Content::class)]
    #[Groups(['listResourceFull','write:itemResource'])]
    #[ApiSubresource]
    private $contents;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['listResourceFull','write:itemResource'])]
    #[ApiSubresource]
    private $type;

    #[ORM\Column(type: 'boolean')]
    //#[Groups(['listResourceFull'])]
    private $modoValid;

    #[ORM\Column(type: 'text')]
    #[Groups(['listResourceFull','write:itemResource'])]
    private $text;

    #[ORM\OneToMany(mappedBy: 'resources', targetEntity: Level::class)]
    private $levels;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['write:itemResource'])]
    private $status;

    #[ORM\OneToMany(mappedBy: 'resources', targetEntity: Comment::class)]
    private $comments;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
        $this->levels = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this ->creationDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getModoValid(): ?bool
    {
        return $this->modoValid;
    }

    public function setModoValid(bool $modoValid): self
    {
        $this->modoValid = $modoValid;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
     /**
     * @return Collection<int, Content>
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(Content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->setResource($this);
        }

        return $this;
    }

    public function removeContent(Content $content): self
    {
        if ($this->contents->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getResource() === $this) {
                $content->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Level>
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    public function addLevel(Level $level): self
    {
        if (!$this->levels->contains($level)) {
            $this->levels[] = $level;
            $level->setResources($this);
        }

        return $this;
    }

    public function removeLevel(Level $level): self
    {
        if ($this->levels->removeElement($level)) {
            // set the owning side to null (unless already changed)
            if ($level->getResources() === $this) {
                $level->setResources(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setResources($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getResources() === $this) {
                $comment->setResources(null);
            }
        }

        return $this;
    }
}
