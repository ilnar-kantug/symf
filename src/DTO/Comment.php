<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Post;
use App\Entity\User;

class Comment
{
    private $author;
    private $body;
    private $post;

    public function set(Post $post, User $author, string $body): void
    {
        $this->author = $author;
        $this->post = $post;
        $this->body = $body;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
