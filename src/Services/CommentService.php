<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Comment;
use App\DTO\Comment as CommentDTO;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function store(CommentDTO $commentDTO): void
    {
        $comment = new Comment();
        $comment->setPost($commentDTO->getPost());
        $comment->setAuthor($commentDTO->getAuthor());
        $comment->setCreatedAt(new \DateTime());
        $comment->setBody($commentDTO->getBody());

        $this->em->persist($comment);
        $this->em->flush();
    }
}