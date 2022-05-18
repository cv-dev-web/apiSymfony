<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource()]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $message;

    #[ORM\Column(type: 'datetime')]
    private $messageDate;

    #[ORM\Column(type: 'boolean')]
    private $vadidationModo;

    #[ORM\ManyToOne(targetEntity: Resource::class, inversedBy: 'comments')]
    private $resources;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'comments')]
    private $answerComment;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'answerComment')]
    private $comments;

    public function __construct()
    {
        $this->answerComment = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessageDate(): ?\DateTimeInterface
    {
        return $this->messageDate;
    }

    public function setMessageDate(\DateTimeInterface $messageDate): self
    {
        $this->messageDate = $messageDate;

        return $this;
    }

    public function getVadidationModo(): ?bool
    {
        return $this->vadidationModo;
    }

    public function setVadidationModo(bool $vadidationModo): self
    {
        $this->vadidationModo = $vadidationModo;

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

    /**
     * @return Collection<int, self>
     */
    public function getAnswerComment(): Collection
    {
        return $this->answerComment;
    }

    public function addAnswerComment(self $answerComment): self
    {
        if (!$this->answerComment->contains($answerComment)) {
            $this->answerComment[] = $answerComment;
        }

        return $this;
    }

    public function removeAnswerComment(self $answerComment): self
    {
        $this->answerComment->removeElement($answerComment);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->addAnswerComment($this);
        }

        return $this;
    }

    public function removeComment(self $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            $comment->removeAnswerComment($this);
        }

        return $this;
    }
}
