<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostRating;
use App\DTO\PostRating as PostRatingDTO;
use App\Services\PostRatingService;
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
    public function like(Post $post, PostRatingService $service, PostRatingDTO $postRating)
    {
        if ($this->failedCsrf('rating')) {
            return $this->fallBackJson('Ain\'t you trying to hack me?!');
        }

        $postRating->set($post, $this->getUser(), PostRating::LIKE_SCORE);

        $service->change($postRating);

        return $this->json(['rating' => $post->sumRating()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/post/{post}/dislike", methods={"POST"}, name="post_dislike")
     * @IsGranted("RATE", subject="post")
     */
    public function disLike(Post $post, PostRatingService $service, PostRatingDTO $postRating)
    {
        if ($this->failedCsrf('rating')) {
            return $this->fallBackJson('Ain\'t you trying to hack me?!');
        }

        $postRating->set($post, $this->getUser(), PostRating::DISLIKE_SCORE);

        $service->change($postRating);

        return $this->json(['rating' => $post->sumRating()], Response::HTTP_CREATED);
    }
}
