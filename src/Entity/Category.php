<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
/**
 * Limitation de l'entité Catégory a la lecture ce celle-ci 
 * ordonée par ordre croissant.
 */
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    order: ['catName' => 'ASC'],
)]
/**
 * Vérification que chaque catégorie sois unique avec retour méssage d'erreur
 */
#[UniqueEntity(
    fields: ['catName'],
    message :"La catégorie {{ value }} est déja utilisée"
    )]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    //#[Groups(['listCategorySimple','listCategoryFull'])]
    #[Groups(['listResourceFull'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    /**
     * Limitation du nombre de caractère entre 2 et 50
     * avec retour méssage d'erreur.
     */
    #[Assert\Length(
        min : 2,
        max : 50,
        minMessage:"La catégorie doit avoir plus de {{ limit }} caractères",
        maxMessage:"La catégorie doit ne doit pas dépasser {{ limit }} caractères"
    )]
    #[Groups(['listResourceFull'])]
    //#[Groups(['listCategorySimple','listCategoryFull','listUserSimple','listResourceSimple'])]
    private $catName;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Resource::class)]
    #[Groups(['listCategoryFull'])]
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

    public function getCatName(): ?string
    {
        return $this->catName;
    }

    public function setCatName(string $catName): self
    {
        $this->catName = $catName;

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
            $resource->setCategory($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getCategory() === $this) {
                $resource->setCategory(null);
            }
        }

        return $this;
    }
}
