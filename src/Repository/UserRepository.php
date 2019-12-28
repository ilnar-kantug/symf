<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getRandUsers(int $limit = null): array
    {
        $query = $this->createQueryBuilder('u')
            ->andWhere('u.confirmToken is NULL')
            ->orderBy('RAND()');

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }

        return $query->getQuery()->getResult();
    }

    public function getUserRating(int $userId): int
    {
        return (int) $this->createQueryBuilder('user')
            ->andWhere('user.id = :userId')
            ->setParameter('userId', $userId)
            ->leftJoin('user.posts', 'posts')
            ->leftJoin('posts.postRatings', 'postsRatings')
            ->select('SUM(postsRatings.score) AS rating')
            ->getQuery()
            ->getSingleResult()
            ['rating'];
    }

    public function searchLikeByEmail(string $email): array
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.email LIKE :email')
            ->setParameter('email', '%' . $email . '%')
            ->select('user.id, user.email')
            ->getQuery()
            ->getResult();
    }

    public function getAdmin(): ?User
    {
        $admin = $this->createQueryBuilder('user')
            ->andWhere('user.roles LIKE :role')
            ->setParameter('role', '%ROLE_ADMIN%')
            ->getQuery()
            ->getResult();
        return $admin[0] ?? null;
    }
}
