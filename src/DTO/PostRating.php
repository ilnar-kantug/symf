<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Post;
use App\Entity\User;

class PostRating
{
    private $author;
    private $score;
    private $post;

    public function set(Post $post, User $author, int $score): void
    {
        $this->author = $author;
        $this->post = $post;
        $this->score = $score;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getScore(): int
    {
        return $this->score;
    }
}
