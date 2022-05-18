<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
/**
 * Limitation de l'entité Type a la lecture ce celle-ci 
 * ordonée par ordre croissant.
 */
#[ApiResource( 
    collectionOperations: ['get'],
    itemOperations: ['get'],
    order: ['typeName' => 'ASC'],
)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['listResourceFull'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $typeName;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Resource::class)]
    #[Groups(['listResourceFull'])]

    //#[ApiSubresource]
    private $resources;

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): self
    {
        $this->typeName = $typeName;

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
            $resource->setType($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getType() === $this) {
                $resource->setType(null);
            }
        }

        return $this;
    }
}
