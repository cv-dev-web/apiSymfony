<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContentRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get_contents' =>
            ['method' => 'get',
            'path'=> '/contents/show_contents',
            'normalization_context'=>['groups' => 'listContents']
            ]
    ],
    itemOperations: [
        'get_content_detail' =>
            ['method' => 'get',
            'path'=> '/contents/{id}/show_content_detail',
            'normalization_context'=>['groups' => 'listContentDetail']
            ]
    ]
    //normalizationContext: ['groups' => ['listContents']]
)]
class Content
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['listCategoryFull','listUserSimple','listContents','listContentDetail'])]
    private $image;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['listCategoryFull','listUserSimple','listContents','listContentDetail'])]
    private $video;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['listCategoryFull','listUserSimple','listContents','listContentDetail'])]
    private $text;

    #[ORM\OneToOne(mappedBy: 'content', targetEntity: Resource::class)]
    #[Groups(['listContentDetail'])]
    private $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->setContent($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getContent() === $this) {
                $resource->setContent(null);
            }
        }

        return $this;
    }
}
