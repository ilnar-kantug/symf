<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Post;
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
class PostRepository extends ServiceEntityRepository
{
    public const RELATIONS = [
        'author',
        'comments',
        'tags'
    ];

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
        return $this->createQueryBuilder('post')
            ->andWhere('post.status = :published')
            ->setParameter('published', Post::STATUS_PUBLISHED)
            ->join('post.author', 'author')
            ->leftJoin('post.tags', 'tags')
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.postRatings', 'post_ratings')
            ->addSelect('author, tags, comments, post_ratings');
    }

    public function getQueryForAuthUserPosts(int $userId): QueryBuilder
    {
        return $this->createQueryBuilder('post')
            ->join('post.author', 'author')
            ->andWhere('post.author = :userId')
            ->setParameter('userId', $userId);
    }

    public function getPostWithComments(int $id): Post
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('post.comments', 'comments')
            ->leftJoin('post.postRatings', 'post_ratings')
            ->join('comments.author', 'comment_author')
            ->addSelect('comments, comment_author, post_ratings')
            ->getQuery()
            ->getSingleResult();
    }

    public function getRandPosts(int $limit = null, array $with = []): array
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.status = ' . Post::STATUS_PUBLISHED)
            ->orderBy('RAND()');

        if ($limit !== null) {
            $query->setMaxResults($limit);
        }

        //TODO extract to trait
        if ($with) {
            foreach ($with as $element) {
                if (in_array($element, self::RELATIONS)) {
                    $query
                        ->join('p.' . $element, $alias = 'p_' . $element)
                        ->addSelect($alias);
                }
            }
        }

        return $query->getQuery()->getResult();
    }
}
