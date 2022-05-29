<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContentRepository::class)]
/**
 * Limitation de l'entité Content a la lecture ce celle-ci.
 */
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get']
)]
class Content
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    //#[Groups(['listCategoryFull','listUserSimple','listContents','listContentDetail'])]
    #[Groups(['listResourceFull'])]
    private $chemin;

    #[ORM\ManyToOne(targetEntity: Resource::class, inversedBy: 'contents')]
    #[ORM\JoinColumn(nullable: false)]
    private $resource;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    public function setChemin(?string $chemin): self
    {
        $this->chemin = $chemin;

        return $this;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $resource): self
    {
        $this->resource = $resource;
        
        return $this;
    }

    public function __toString()
    {
        return $this->chemin;
    }
}
