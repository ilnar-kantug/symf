<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommentController extends BaseController
{
    /**
     * @Route("/post/{id}/comment", methods={"POST"}, name="post_comment_store")
     * @Security("is_granted('ROLE_USER')")
     */
    public function store(Post $post)
    {
        if (! $this->isCsrfTokenValid('comment_post', $this->request->get('csrf'))) {
            return $this->fallBackWithError('Ain\'t you trying to hack me?!');
        }
        if (empty($body = $this->request->get('body'))) {
            return $this->fallBackWithError('Empty comment!');
        }

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setAuthor($this->getUser());
        $comment->setCreatedAt(new \DateTime());
        $comment->setBody($body);

        $this->em->persist($comment);
        $this->em->flush();

        return $this->redirectBackWithSuccess('Your comment is added!');
    }
}
