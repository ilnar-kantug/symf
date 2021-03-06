<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

class TagFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'tags', function () {
            $tag = new Tag();
            $tag->setName($this->faker->unique()->word);
            return $tag;
        });

        $manager->flush();
    }
}
