<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends BaseController
{
    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function show(int $id, PostRepository $postRepository)
    {
        return $this->render('post/show.html.twig', [
            'post' => $postRepository->getPostWithComments($id),
        ]);
    }

    /**
     * @Route("/post", methods={"POST"}, name="post_store")
     * @Security("is_granted('ROLE_USER')")
     */
    public function store(PostRepository $postRepository)
    {
        if (! $this->isCsrfTokenValid('comment_post', $this->request->get('csrf'))) {
            return $this->fallBackWithError('Ain\'t you trying to hack me?!');
        }
        if (empty($body = $this->request->get('body'))) {
            return $this->fallBackWithError('Empty comment!');
        }

        $comment = new Comment();
        $comment->setPost($postRepository->find((int) $this->request->get('post_id')));
        $comment->setAuthor($this->getUser());
        $comment->setCreatedAt(new \DateTime());
        $comment->setBody($body);

        $this->em->persist($comment);
        $this->em->flush();

        return $this->redirectBackWithSuccess('Your comment is added!');
    }
}
