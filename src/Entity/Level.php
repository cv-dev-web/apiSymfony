<?php

namespace App\Entity;

use App\Repository\LevelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
/**
 * Vérification que les champs users et resources sont unique
 */
#[UniqueEntity(
    fields: ['users','resources']
    )]
#[ApiResource()]
class Level
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $favorite;

    #[ORM\Column(type: 'boolean')]
    private $exploit;

    #[ORM\Column(type: 'boolean')]
    private $leftAside;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'levels')]
    private $users;

    #[ORM\ManyToOne(targetEntity: Resource::class, inversedBy: 'levels')]
    private $resources;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFavorite(): ?bool
    {
        return $this->favorite;
    }

    public function setFavorite(bool $favorite): self
    {
        $this->favorite = $favorite;

        return $this;
    }

    public function getExploit(): ?bool
    {
        return $this->exploit;
    }

    public function setExploit(bool $exploit): self
    {
        $this->exploit = $exploit;

        return $this;
    }

    public function getLeftAside(): ?bool
    {
        return $this->leftAside;
    }

    public function setLeftAside(bool $leftAside): self
    {
        $this->leftAside = $leftAside;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getResources(): ?Resource
    {
        return $this->resources;
    }

    public function setResources(?Resource $resources): self
    {
        $this->resources = $resources;

        return $this;
    }
}
