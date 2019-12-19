<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\PostRating;
use App\Helpers\Arr;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class PostRatingFixture extends BaseFixture implements DependentFixtureInterface
{
    private $postRepository;
    private $userRepository;
    private $em;

    public function __construct(
        PostRepository $postRepository,
        UserRepository $userRepository,
        EntityManagerInterface $em
    )
    {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    protected function loadData(ObjectManager $manager): void
    {
        $posts = $this->postRepository->getRandPosts(10, ['author']);
        $users = $this->userRepository->getRandUsers(10);

        foreach ($posts as $post) {
            foreach ($users as $user) {
                if ($post->getAuthor()->getId() === $user->getId()) {
                    continue;
                }
                $rating = new PostRating();
                $rating->setPost($post);
                $rating->setUser($user);
                $rating->setScore(Arr::randOne([PostRating::DISLIKE_SCORE, PostRating::LIKE_SCORE]));
                $this->em->persist($rating);
            }
        }
        $this->em->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            PostFixture::class
        ];
    }
}
