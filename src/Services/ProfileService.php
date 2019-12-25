<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\Paginator;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProfileService
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

    public function __construct(PostRepository $postRepository, PaginatorInterface $paginator, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
        $this->userRepository = $userRepository;
    }

    public function getUserPost(int $page, int $userId): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->getQueryForAuthUserPosts($userId),
            $page,
            Paginator::PROFILE_PER_PAGE
        );
    }

    public function getUserRating(int $userId): int
    {
        return $this->userRepository->getUserRating($userId);
    }
}
