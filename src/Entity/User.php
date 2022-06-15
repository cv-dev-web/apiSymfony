<?php

namespace App\Entity;

use App\Entity\Grade;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
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
    //normalizationContext: ['groups' => ['read']],
    //denormalizationContext: ['groups' => ['post']],
   collectionOperations: [ 
        'get',
        'post_user' => [ 
            'method' => 'POST',
            'path'=> '/create_user',
            'normalization_context' => ['groups' => ['write:itemUser']]
        ]
        
    ]
    
)]
#[ORM\EntityListeners(['App\EntityListener\UserListener'])]
class User implements PasswordAuthenticatedUserInterface,UserInterface
{
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_MODO = "ROLE_MODO";
    const ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";
    const ROLE_CITOYEN = "ROLE_CITOYEN";
    const DEFAULT_ROLE = "ROLE_CITOYEN";

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

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    /**
     * Vérification de la bonne syntaxe d'un EMAIL
     */
    #[Assert\Email(
        message: 'L\'Email {{ value }} n\'est pas valide.',
    )]
    
    
    #[Groups(['listResourceFull','read','write:itemUser'])]
    private $email;

    //#[ORM\Column(type: 'json')]
    //private $roles = [];

    #[ORM\Column(type: 'string', length: 255)]
    //#[Groups(['write:itemUser'])]
    #[Assert\NotBlank()]
    private $password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['listResourceFull','read','write:itemUser'])]
    private $avatar;

    #[ORM\Column(type: 'boolean')]
    //#[Groups(['write:itemUser'])]
    private $isActif;

    #[ORM\Column(type: 'boolean')]
    //#[Groups(['write:itemUser'])]
    private $firstConnexion;

    #[Groups(['write:itemUser'])]
    private ?String $plainPassword = null;

    #[ORM\ManyToOne(targetEntity: Grade::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    //#[Groups(['write:itemUser'])]
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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private $comments;

   

    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->levels = new ArrayCollection();
        $this ->userCreationDate = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->roles = self::DEFAULT_ROLE;
        $this->isActif = false;
        $this->firstConnexion = true;
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

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {

        return $this->grade;
    }

    /**
     * affectation des roles utilisateurs en rapport au grade
     *
     * @param array $grade
     * @return self
     */
    public function setRoles(?Grade $grade): self
    {
        return match($grade)
        {
            "super administrateur"=> $this->roles = self::ROLE_SUPER_ADMIN,
            "administrateur"=> $this->roles = self::ROLE_ADMIN,
            "modérateur"=> $this->roles = self::ROLE_MODO,
            "citoyen"=> $this->roles = self::ROLE_CITOYEN

        };

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->lastName;
    }

    

    /**
     * Get the value of plainPassword
     */ 
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @return  self
     */ 
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }
}
