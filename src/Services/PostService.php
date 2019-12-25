<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRatingRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\Post as PostDTO;

class PostService
{
    /**
     * @var PostRatingRepository
     */
    private $postRatingRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PostRepository
     */
    private $postRepository;

    public function __construct(
        PostRepository $postRepository,
        PostRatingRepository $postRatingRepository,
        EntityManagerInterface $em
    ) {
        $this->postRatingRepository = $postRatingRepository;
        $this->em = $em;
        $this->postRepository = $postRepository;
    }

    public function create(PostDTO $postDTO): void
    {
        $post = new Post();
        $post->setTitle($postDTO->getTitle());
        $post->setBody($postDTO->getBody());
        $post->setPreview($postDTO->getPreview());
        $post->setAuthor($postDTO->getAuthor());
        $post->setCreatedAt(new \DateTime());
        $post->setStatus(Post::STATUS_DRAFT);

        $this->em->persist($post);
        $this->em->flush();
    }

    public function update(Post $post): void
    {
        $this->em->persist($post);
        $this->em->flush();
    }

    public function remove(Post $post): void
    {
        $this->em->remove($post);
        $this->em->flush();
    }

    public function publish(Post $post): void
    {
        $post->setStatus(Post::STATUS_ON_MODERATION);
        $this->em->flush();
    }

    public function getPostWithComments(int $id): Post
    {
        return $this->postRepository->getPostWithComments($id);
    }

    public function getPostUsersRate(Post $post, User $user): ?int
    {
        $postRating = $this->postRatingRepository->findOneBy(['user' => $user, 'post' => $post]);
        return $postRating ? $postRating->getScore() : null;
    }
}
