<?php

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
            ->join('post.tags', 'tags')
            ->addSelect('author, tags');
    }

    public function getPostWithComments(int $id): Post
    {
        return $this->createQueryBuilder('post')
            ->andWhere('post.id = :id')
            ->setParameter('id', $id)
            ->join('post.comments', 'comments')
            ->join('comments.author', 'comment_author')
            ->addSelect('comments, comment_author')
            ->getQuery()
            ->getSingleResult();
    }
}
