<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
/**
 * Vérification que chaque EMAIL sois unique avec retour méssage d'erreur
 */
#[UniqueEntity(
    fields: ['email'],
    message :"L'email {{ value }} est déja utilisée"
    )]
/**
 * l'entité User par ordre croissant par nom/prenom.
 */
#[ApiResource(
    order: ['lastName' => 'ASC','firstName' => 'ASC'],
    normalizationContext: ['groups' => ['read']],
    //denormalizationContext: ['groups' => ['post']],
   collectionOperations: [ 
        'post' => [ 
            'normalization_context' => ['groups' => ['write:itemUser','write:itemGrade']]
        ]
    ]
    
)]
class User implements PasswordAuthenticatedUserInterface,UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['listResourceFull'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['listResourceFull','read','write:itemUser'])]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['listResourceFull','read','write:itemUser'])]
    private $firstName;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['read','write:itemUser'])]
    private $birthDate;

    #[ORM\Column(type: 'string', length: 255)]
    /**
     * Vérification de la bonne syntaxe d'un EMAIL
     */
    #[Assert\Email(
        message: 'L\'Email {{ value }} n\'est pas valide.',
    )]
    #[Groups(['listResourceFull','read','write:itemUser'])]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['write:itemUser'])]
    private $password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['listResourceFull','read','write:itemUser'])]
    private $avatar;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['write:itemUser'])]
    private $isActif;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['write:itemUser'])]
    private $firstConnexion;

    #[ORM\ManyToOne(targetEntity: Grade::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['write:itemUser'])]
    private $grade;

    #[ORM\Column(type: 'string', length: 10)]
    #[Groups(['read','write:itemUser'])]
    private $phone;

    #[ORM\Column(type: 'datetime')]
    //#[Groups(['write:itemUser'])]
    private $userCreationDate;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Resource::class)]
    //#[Groups(['write'])]
    private $resources;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Level::class)]
    //#[Groups(['write'])]
    private $levels;

   

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->levels = new ArrayCollection();
        $this ->userCreationDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function getFirstConnexion(): ?bool
    {
        return $this->firstConnexion;
    }

    public function setFirstConnexion(bool $firstConnexion): self
    {
        $this->firstConnexion = $firstConnexion;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): self
    {
        $this->grade = $grade;

        return $this;
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUserCreationDate(): ?\DateTimeInterface
    {
        return $this->userCreationDate;
    }

    public function setUserCreationDate(\DateTimeInterface $userCreationDate): self
    {
        $this->userCreationDate = $userCreationDate;

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
            $resource->setUser($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getUser() === $this) {
                $resource->setUser(null);
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
            $level->setUsers($this);
        }

        return $this;
    }

    public function removeLevel(Level $level): self
    {
        if ($this->levels->removeElement($level)) {
            // set the owning side to null (unless already changed)
            if ($level->getUsers() === $this) {
                $level->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored in a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
            return ['ROLE_USER'];
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(){

    }

    /**
     * Returns the identifier for this user (e.g. its username or email address).
     */
    public function getUserIdentifier(): string
    {
       return $this->getEmail();
    }

    
}
