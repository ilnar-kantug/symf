<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\CacheExpiring;
use App\Constants\Paginator;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

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
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var TagAwareCacheInterface
     */
    private $cache;

    public function __construct(
        PostRepository $postRepository,
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        TagAwareCacheInterface $cache
    ) {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
        $this->userRepository = $userRepository;
        $this->cache = $cache;
    }

    public function getPublishedPosts(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->getQueryForAllPublished(),
            $page,
            Paginator::POST_PER_PAGE
        );
    }

    public function getTopRatedPosts(): array
    {
        return $this->cache->get('top_posts', function (ItemInterface $item) {
            $item->expiresAfter(CacheExpiring::TOP_POSTS);
            return $this->postRepository->getTopRatedPosts();
        });
    }

    public function getTopRatedAuthors(): array
    {
        return $this->cache->get('top_authors', function (ItemInterface $item) {
            $item->expiresAfter(CacheExpiring::TOP_AUTHORS);
            return $this->userRepository->getTopRatedAuthors();
        });
    }
}
