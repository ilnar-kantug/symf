<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class Post
{
    public const STATUS_DRAFT = 1;
    public const STATUS_ON_MODERATION = 2;
    public const STATUS_DECLINED = 3;
    public const STATUS_PUBLISHED = 4;

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_ON_MODERATION,
        self::STATUS_DECLINED,
        self::STATUS_PUBLISHED,
    ];

    private $id;

    /**
     * @Assert\NotBlank(message="Please enter the title")
     * @Assert\Length(max="255", maxMessage="The title is too long")
     */
    private $title;

    /**
     * @Assert\NotBlank(message="Please enter the body of a post")
     */
    private $body;

    private $createdAt;

    private $status;

    private $tags;

    private $author;

    private $comments;

    private $postRatings;

    /**
     * @Assert\NotBlank(message="Please enter the preview")
     * @Assert\Length(max="300", maxMessage="The preview is too long")
     */
    private $preview;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->postRatings = new ArrayCollection();
    }

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostRating[]
     */
    public function getPostRatings(): Collection
    {
        return $this->postRatings;
    }

    public function addPostRating(PostRating $postRating): self
    {
        if (!$this->postRatings->contains($postRating)) {
            $this->postRatings[] = $postRating;
            $postRating->setPost($this);
        }

        return $this;
    }

    public function removePostRating(PostRating $postRating): self
    {
        if ($this->postRatings->contains($postRating)) {
            $this->postRatings->removeElement($postRating);
            // set the owning side to null (unless already changed)
            if ($postRating->getPost() === $this) {
                $postRating->setPost(null);
            }
        }

        return $this;
    }

    public function sumRating(): int
    {
        $sum = 0;
        if (! $this->postRatings->isEmpty()) {
            foreach ($this->postRatings as $postRating) {
                $sum += $postRating->getScore();
            }
        }
        return $sum;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }
}
