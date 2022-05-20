<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RelatedUserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RelatedUserRepository::class)]
/**
 * Vérification que les champs users et resources sont unique
 */
#[UniqueEntity(
    fields: ['userOne','userTwo']
    )]
/**
 * Limitation de l'entité RelatedUser a la lecture ce celle-ci.
 */
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get']
)]
class RelatedUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $idRelationUser;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $userOne;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $userTwo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRelationUser(): ?int
    {
        return $this->idRelationUser;
    }

    public function setIdRelationUser(?int $idRelationUser): self
    {
        $this->idRelationUser = $idRelationUser;

        return $this;
    }

    public function getUserOne(): ?User
    {
        return $this->userOne;
    }

    public function setUserOne(?User $userOne): self
    {
        $this->userOne = $userOne;

        return $this;
    }

    public function getUserTwo(): ?User
    {
        return $this->userTwo;
    }

    public function setUserTwo(?User $userTwo): self
    {
        $this->userTwo = $userTwo;

        return $this;
    }
}
