<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\PostRatingRepository;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class PostController extends BaseController
{
    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function show(int $id, PostRepository $postRepository, PostRatingRepository $postRatingRepository, RouterInterface $router)
    {
        $post = $postRepository->getPostWithComments($id);
        $user = $this->getUser();
        $rate = null;
        if ($user) {
            $postRating = $postRatingRepository->findOneBy(['user' => $user, 'post' => $post]);
            $rate = $postRating ? $postRating->getScore() : null;
        }
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'rate' => $rate,
            'dislike_route' => $router->generate('post_dislike', ['post' => $post->getId()]),
            'like_route' =>  $router->generate('post_like', ['post' => $post->getId()])
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
