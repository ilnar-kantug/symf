<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixture extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'draft_posts', function () {
            return $this->getPostByStatus(Post::STATUS_DRAFT);
        });

        $this->createMany(30, 'published_posts', function () {
            return $this->getPostByStatus(Post::STATUS_PUBLISHED);
        });

        $manager->flush();
    }

    public function getPostByStatus(int $status)
    {
        $post = new Post();
        $post->setTitle($this->faker->sentence);
        $post->setBody($this->faker->realText());
        $post->setCreatedAt($this->faker->dateTimeThisMonth);
        $post->setStatus($status);

        /** @var User $author */
        $author = $this->getRandomReference('confirmed_users');
        $post->setAuthor($author);

        $tags = $this->getRandomReferences('tags', $this->faker->numberBetween(0, 5));
        foreach ($tags as $tag) {
            $post->addTag($tag);
        }

        return $post;
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            TagFixture::class
        ];
    }
}
