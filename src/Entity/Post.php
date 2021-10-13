<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter as FilterSearchFilter;
use App\Controller\PostPublishController;
use App\Controller\PostsCountController;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
#[
    ApiResource(
        normalizationContext: [
            'groups' => ['read:collection', 'read:item', 'read:Post'],
            'openapi_definition_name' => 'collection'
        ],
        denormalizationContext: ['groups' => ['put:Post']],
        paginationClientItemsPerPage: 2,
        paginationMaximumItemsPerPage: 5,
        collectionOperations: [
            'get',
            'post',
            'count' => [
                'method' => 'GET',
                'path'   => '/posts/count',
                'controller' => PostsCountController::class,
                'filters' => [],
                'pagination_enabled' => false,
                'openapi_context' => [
                    'summary' => 'Afficher le nombre total des articles',
                    'parameters' => [],
                ]
            ]
        ],
        itemOperations: [
            'get' => [
                'normalization_context' => [
                    'groups' => ['read:collection', 'read:item', 'read:Post'],
                    'openapi_definition_name' => 'Details'
                ]
            ], 'put', 'delete', 'patch',
            'publish' => [
                'method' => 'POST',
                'path' => '/posts/{id}/publish',
                'controller' => PostPublishController::class
            ]

        ]
    ),
    ApiFilter(FilterSearchFilter::class, properties: ['id' => 'exact', 'title' => 'partial'])
]

class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:collection'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:collection', 'put:Post']),
        Length(min: 5, groups: ['create:Post'])
    ]
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:collection', 'put:Post'])]
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(['read:item', 'put:Post'])]
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['put:Post'])]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['put:Post'])]
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="posts", cascade={"persist"})
     */
    #[
        Groups(['read:item', 'put:Post']),
        Valid()
    ]
    private $category;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    #[Groups(['read:collection'])]
    private $onLine;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getOnLine(): ?bool
    {
        return $this->onLine;
    }

    public function setOnLine(bool $onLine): self
    {
        $this->onLine = $onLine;

        return $this;
    }
}
