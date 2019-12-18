<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(200, 'comments', function () {
            $comment = new Comment();
            $comment->setBody($this->faker->realText());
            $comment->setCreatedAt($this->faker->dateTimeThisMonth);

            /** @var User $author */
            $author = $this->getRandomReference('confirmed_users');
            $comment->setAuthor($author);

            /** @var Post $post */
            $post = $this->getRandomReference('published_posts');
            $comment->setPost($post);

            return $comment;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            PostFixture::class
        ];
    }
}
