<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ResourceRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ApiResource()]
class Resource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['listCategoryFull','listUserSimple','listContentDetail'])]
    private $title;

    #[ORM\Column(type: 'boolean')]
    private $visibility;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['listCategoryFull','listUserSimple'])]
    private $creationDate;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['listCategoryFull'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['listUserSimple'])]
    private $category;

    #[ORM\OneToOne(targetEntity: Content::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['listCategoryFull','listUserSimple'])]
    private $content;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'resources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['listCategoryFull','listUserSimple'])]
    private $type;

    #[ORM\Column(type: 'boolean')]
    private $modoValid;

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

        return $this;
    }

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(?Content $content): self
    {
        $this->content = $content;

        return $this;
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
}
