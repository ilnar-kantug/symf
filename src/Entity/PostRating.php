<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PostRating
{
    public const LIKE_SCORE = 1;
    public const DISLIKE_SCORE = -1;

    private $id;

    /**
     * @Assert\NotNull()
     */
    private $score;

    /**
     * @Assert\NotNull()
     */
    private $user;

    /**
     * @Assert\NotNull()
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
