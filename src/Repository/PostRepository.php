<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
use App\Repository\Concerns\WithRelations;
use App\Repository\Contracts\EagerLoadRelations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository implements EagerLoadRelations
{
    use WithRelations;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(
        ManagerRegistry $registry,
        PaginatorInterface $paginator
    ) {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    public function getQueryForAllPublished(): QueryBuilder
    {
        $query = $this->createQueryBuilder($alias = 'post')
            ->andWhere('post.status = :published')
            ->setParameter('published', Post::STATUS_PUBLISHED);

        $this->fetchRelations(['author', 'tags', 'comments', 'postRatings'], $alias, $query);

        return $query;
    }

    public function getQueryForAuthUserPosts(int $userId): QueryBuilder
    {
        return $this->createQueryBuilder('post')
            ->join('post.author', 'author')
            ->andWhere('post.author = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('post.createdAt', 'DESC');
    }

    public function getPostWithComments(int $id): Post
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.postRatings', 'post_ratings')
            ->leftJoin('comments.author', 'comment_author')
            ->addSelect('comments, comment_author, post_ratings')
            ->getQuery()
            ->getSingleResult();
    }

    public function getRandPosts(int $limit = null, array $withRelations = []): array
    {
        $query = $this->createQueryBuilder($alias = 'p')
            ->andWhere('p.status = ' . Post::STATUS_PUBLISHED)
            ->orderBy('RAND()');

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }

        if ($withRelations) {
            $this->fetchRelations($withRelations, $alias, $query);
        }

        return $query->getQuery()->getResult();
    }

    public function getQueryForAllNotDraft(): QueryBuilder
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.status <> :published')
            ->setParameter('published', Post::STATUS_DRAFT)
            ->join('post.author', 'author')
            ->addSelect('author')
            ->orderBy('post.id', 'DESC');
    }

    public function getRelationsNames(): array
    {
        return [
            'author',
            'comments',
            'postRatings',
            'tags'
        ];
    }
}
