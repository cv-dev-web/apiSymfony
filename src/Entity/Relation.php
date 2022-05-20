<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RelationRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
/**
 * Limitation de l'entité Level a la lecture ce celle-ci.
 */
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get']
)]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $relationName;

    #[ORM\ManyToMany(targetEntity: Resource::class)]
    private $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelationName(): ?string
    {
        return $this->relationName;
    }

    public function setRelationName(string $relationName): self
    {
        $this->relationName = $relationName;

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
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        $this->resources->removeElement($resource);

        return $this;
    }
}
