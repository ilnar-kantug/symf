<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRatingRepository;
use App\Repository\PostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class PostController extends BaseController
{
    /**
     * @Route("/post/create", name="post_create")
     * @Security("is_granted('ROLE_USER')")
     */
    public function create()
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = new Post();
            $post->setTitle($form->getData()->getTitle());
            $post->setBody($form->getData()->getBody());
            $post->setPreview($form->getData()->getPreview());
            $post->setAuthor($this->getUser());
            $post->setCreatedAt(new \DateTime());
            $post->setStatus(Post::STATUS_DRAFT);

            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRouteWithSuccess(
                'Success! You created a post!',
                'profile'
            );
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function show(
        int $id,
        PostRepository $postRepository,
        PostRatingRepository $postRatingRepository,
        RouterInterface $router
    ) {
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
}
