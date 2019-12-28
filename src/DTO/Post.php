<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\User;

class Post
{
    private $author;
    private $title;
    private $preview;
    private $body;
    private $tags;

    public function set(User $author, string $title, string $preview, string $body, array $tags): void
    {
        $this->author = $author;
        $this->title = $title;
        $this->preview = $preview;
        $this->body = $body;
        $this->tags = $tags;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getPreview(): string
    {
        return $this->preview;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTags(): array
    {
        return $this->tags;
    }
}
