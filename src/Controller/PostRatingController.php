<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostRating;
use App\Repository\PostRatingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class PostRatingController extends BaseController
{
    /**
     * @Route("/post/{post}/rate", methods={"GET"}, name="post_destroy_rate")
     */
    public function destroy(Post $post, PostRatingRepository $postRatingRepository)
    {
        //voter

        if ($this->failedCsrf('destroy_rate')) {
            return $this->fallBackJson('Ain\'t you trying to hack me?!');
        }

        $user = $this->getUser();
        $rate = $postRatingRepository->findOneBy(['user' => $user, 'post' => $post]);

        if (is_null($rate)) {
            throw new \Exception('no rate');
        }

        $this->em->remove($rate);
        $this->em->flush();

        return $this->json('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/post/{post}/like", methods={"GET"}, name="post_like")
     */
    public function like(Post $post, PostRatingRepository $postRatingRepository)
    {
        //voter

        if ($this->failedCsrf('like_post')) {
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

        return $this->json('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/post/{post}/dislike", methods={"GET"}, name="post_dislike")
     */
    public function disLike(Post $post, PostRatingRepository $postRatingRepository)
    {
        //voter

        if ($this->failedCsrf('dislike_post')) {
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

        return $this->json('', Response::HTTP_CREATED);
    }

}
