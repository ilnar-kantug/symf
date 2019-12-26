<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Constants\Paginator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $em
    ) {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
        $this->em = $em;
    }

    public function getUsers(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->findAll(),
            $page,
            Paginator::USER_PER_PAGE
        );
    }
}
