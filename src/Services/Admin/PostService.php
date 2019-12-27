<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Constants\Paginator;
use App\DTO\Filters\Post as PostFilterDto;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class PostService
{
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        PostRepository $postRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $em
    ) {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
        $this->em = $em;
    }

    public function getNotDraftPosts(int $page, PostFilterDto $postFilterDTO): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->getQueryForAllNotDraft($postFilterDTO),
            $page,
            Paginator::POST_PER_PAGE
        );
    }

    public function updatePost(Post $post, int $status): void
    {
        $post->setStatus($status);
        $this->em->persist($post);
        $this->em->flush();
    }
}
