<?php

namespace App\Entity;

use App\Repository\ArticleCommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleCommentRepository::class)]
class ArticleComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'articleComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'articleCommentResponses')]
    private ?self $commentResponse = null;

    #[ORM\OneToMany(mappedBy: 'commentResponse', targetEntity: self::class)]
    private Collection $articleCommentResponses;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->articleCommentResponses = new ArrayCollection();

        $this->updatedAt = new \DateTimeImmutable();
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getCommentResponse(): ?self
    {
        return $this->commentResponse;
    }

    public function setCommentResponse(?self $commentResponse): static
    {
        $this->commentResponse = $commentResponse;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getArticleCommentResponses(): Collection
    {
        return $this->articleCommentResponses;
    }

    public function addArticleCommentResponse(self $articleCommentResponse): static
    {
        if (!$this->articleCommentResponses->contains($articleCommentResponse)) {
            $this->articleCommentResponses->add($articleCommentResponse);
            $articleCommentResponse->setCommentResponse($this);
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
