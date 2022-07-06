<?php

namespace App\Entity;


use App\Entity\Content;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContentRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\ApiPlatformController\CreateContentAction;

#[ORM\Entity(repositoryClass: ContentRepository::class)]

/**
 * @Vich\Uploadable
 */
#[ApiResource(
    iri: 'http://schema.org/contents',
    normalizationContext: ['groups' => ['media_object:read']],
    itemOperations: ['get'],
    collectionOperations: [
        'get',
        'post' => [
            'controller' => CreateContentAction::class,
            'deserialize' => false,
            'validation_groups' => ['Default', 'media_object_create'],
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]
)]
class Content
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    
    #[ApiProperty(iri: 'http://schema.org/contentUrl')]
    #[Groups(['media_object:read'])]
    public ?string $contentUrl = null;

    /**
     * @Vich\UploadableField(mapping="contents", fileNameProperty="chemin")
     */
    #[Assert\NotNull(groups: ['media_object_create'])]
    public ?File $file = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    //#[Groups(['listCategoryFull','listUserSimple','listContents','listContentDetail'])]
    #[Groups(['listResourceFull'])]
    private $chemin = null;

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
