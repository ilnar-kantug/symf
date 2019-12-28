<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findManyById($value): array
    {
        return $this->createQueryBuilder('tag')
            ->andWhere('tag.id IN (:val)')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function searchLikeByName(string $name): array
    {
        return $this->createQueryBuilder('tag')
            ->andWhere('tag.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->select('tag.id, tag.name')
            ->getQuery()
            ->getResult();
    }
}
