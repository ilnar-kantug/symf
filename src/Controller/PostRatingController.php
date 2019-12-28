<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostRating;
use App\DTO\PostRating as PostRatingDTO;
use App\Services\PostRatingService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("ROLE_USER")
 */
class PostRatingController extends WebController
{
    /**
     * @IsGranted("RATE", subject="post")
     */
    public function like(Post $post, PostRatingService $service, PostRatingDTO $postRating): JsonResponse
    {
        if ($this->failedCsrf('rating')) {
            return $this->fallBackJson('Ain\'t you trying to hack me?!');
        }

        $postRating->set($post, $this->getUser(), PostRating::LIKE_SCORE);

        $service->change($postRating);

        return $this->json(['rating' => $post->sumRating()], Response::HTTP_CREATED);
    }

    /**
     * @IsGranted("RATE", subject="post")
     */
    public function disLike(Post $post, PostRatingService $service, PostRatingDTO $postRating): JsonResponse
    {
        if ($this->failedCsrf('rating')) {
            return $this->fallBackJson('Ain\'t you trying to hack me?!');
        }

        $postRating->set($post, $this->getUser(), PostRating::DISLIKE_SCORE);

        $service->change($postRating);

        return $this->json(['rating' => $post->sumRating()], Response::HTTP_CREATED);
    }
}
