<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostRating;
use App\Repository\PostRatingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class PostRatingController extends BaseController
{
    /**
     * @Route("/post/{post}/like", methods={"POST"}, name="post_like")
     * @IsGranted("RATE", subject="post")
     */
    public function like(Post $post, PostRatingRepository $postRatingRepository)
    {
        if ($this->failedCsrf('rating')) {
            return $this->fallBackJson('Ain\'t you trying to hack me?!');
        }

        $user = $this->getUser();
        $newRate = null;
        /** @var PostRating $rate */
        $rate = $postRatingRepository->findOneBy(['user' => $user, 'post' => $post]);

        if ($rate) {
            $rate->setScore(PostRating::LIKE_SCORE);
        } else {
            $newRate = new PostRating();
            $newRate->setUser($user);
            $newRate->setPost($post);
            $newRate->setScore(PostRating::LIKE_SCORE);
            $this->em->persist($newRate);
        }

        $this->em->flush();

        return $this->json(['rating' => $post->sumRating()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/post/{post}/dislike", methods={"POST"}, name="post_dislike")
     * @IsGranted("RATE", subject="post")
     */
    public function disLike(Post $post, PostRatingRepository $postRatingRepository)
    {
        if ($this->failedCsrf('rating')) {
            return $this->fallBackJson('Ain\'t you trying to hack me?!');
        }

        $user = $this->getUser();
        $newRate = null;
        /** @var PostRating $rate */
        $rate = $postRatingRepository->findOneBy(['user' => $user, 'post' => $post]);

        if ($rate) {
            $rate->setScore(PostRating::DISLIKE_SCORE);
        } else {
            $newRate = new PostRating();
            $newRate->setUser($user);
            $newRate->setPost($post);
            $newRate->setScore(PostRating::DISLIKE_SCORE);
            $this->em->persist($newRate);
        }

        $this->em->flush();

        return $this->json(['rating' => $post->sumRating()], Response::HTTP_CREATED);
    }

}
