<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Comment;
use App\Entity\Post;
use App\Services\CommentService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommentController extends WebController
{
    /**
     * @Route("/post/{id}/comment", methods={"POST"}, name="post_comment_store")
     * @Security("is_granted('ROLE_USER')")
     */
    public function store(Post $post, CommentService $service, Comment $commentDTO)
    {
        if (! $this->isCsrfTokenValid('comment_post', $this->request->get('csrf'))) {
            return $this->fallBackWithError('Ain\'t you trying to hack me?!');
        }
        if (empty($body = $this->request->get('body'))) {
            return $this->fallBackWithError('Empty comment!');
        }

        $commentDTO->set($post, $this->getUser(), $body);

        $service->store($commentDTO);

        return $this->redirectBackWithSuccess('Your comment is added!');
    }
}
