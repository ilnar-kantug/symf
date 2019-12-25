<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\PostRating;
use App\Repository\PostRatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\PostRating as PostRatingDTO;

class PostRatingService
{
    /**
     * @var PostRatingRepository
     */
    private $postRatingRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PostRatingRepository $postRatingRepository, EntityManagerInterface $em)
    {
        $this->postRatingRepository = $postRatingRepository;
        $this->em = $em;
    }

    public function change(PostRatingDTO $postRating): void
    {
        $newRate = null;
        /** @var PostRating $rate */
        $rate = $this->postRatingRepository->findOneBy(['user' => $postRating->getAuthor(), 'post' => $postRating->getPost()]);

        if ($rate) {
            $rate->setScore($postRating->getScore());
        } else {
            $newRate = new PostRating();
            $newRate->setUser($postRating->getAuthor());
            $newRate->setPost($postRating->getPost());
            $newRate->setScore($postRating->getScore());
            $this->em->persist($newRate);
        }

        $this->em->flush();
    }
}
