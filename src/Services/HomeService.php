<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\Paginator;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class HomeService
{
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
    }

    public function getPublishedPosts(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->getQueryForAllPublished(),
            $page,
            Paginator::POST_PER_PAGE
        );
    }
}
