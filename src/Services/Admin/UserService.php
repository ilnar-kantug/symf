<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Constants\Paginator;
use App\Entity\User;
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

    public function getUsers(int $page, ?string $userId): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->findBy(is_null($userId) ? [] : ['id' => (int) $userId], ['id' => 'DESC']),
            $page,
            Paginator::USER_PER_PAGE
        );
    }

    public function banUser(User $user): void
    {
        $user->setStatus(User::STATUS_BANNED);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function activateUser(User $user): void
    {
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setConfirmToken(null);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function search(string $email): array
    {
        return $this->userRepository->searchLikeByEmail($email);
    }
}
