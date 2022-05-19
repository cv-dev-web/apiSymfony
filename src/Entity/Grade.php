<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GradeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
/**
 * Limitation de l'entité Grade a la lecture ce celle-ci.
 */
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    //denormalizationContext: ['groups' => ['post']]
)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
   // #[Groups(['write:itemGrade'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)] private $gradeName;

    #[ORM\OneToMany(mappedBy: 'grade', targetEntity: User::class)]
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGradeName(): ?string
    {
        return $this->gradeName;
    }

    public function setGradeName(string $gradeName): self
    {
        $this->gradeName = $gradeName;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGrade($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getGrade() === $this) {
                $user->setGrade(null);
            }
        }

        return $this;
    }
}
