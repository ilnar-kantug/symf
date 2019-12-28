<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\DTO\Post as PostDTO;
use App\Form\PostType;
use App\Services\PostService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PostController extends WebController
{
    /**
     * @var PostService
     */
    private $postService;

    public function __construct(
        RequestStack $requestStack,
        PostService $postService
    ) {
        parent::__construct($requestStack);
        $this->postService = $postService;
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function create(PostDTO $post)
    {
        $form = $this->createForm(PostType::class);
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->set(
                $this->getUser(),
                $form->getData()->getTitle(),
                $form->getData()->getPreview(),
                $form->getData()->getBody()
            );

            $this->postService->create($post);

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
     * @IsGranted("ROLE_USER")
     * @IsGranted("CHANGE_POST", subject="post")
     */
    public function edit(Post $post)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->update($post);

            return $this->redirectToRouteWithSuccess(
                'Success! You changed the post!',
                'profile'
            );
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("REMOVE_POST", subject="post")
     */
    public function remove(Post $post): RedirectResponse
    {
        $this->postService->remove($post);

        return $this->redirectToRouteWithSuccess(
            'Success! You removed the post!',
            'profile'
        );
    }

    /**
     * @IsGranted("ROLE_USER")
     * @IsGranted("CHANGE_POST", subject="post")
     */
    public function publish(Post $post): RedirectResponse
    {
        $this->postService->publish($post);

        return $this->redirectToRouteWithSuccess(
            'Success! You send post to publishing!',
            'profile'
        );
    }

    public function show(
        int $id,
        RouterInterface $router
    ): Response {
        $post = $this->postService->getPostWithComments($id);

        $rate = null;
        if ($user = $this->getUser()) {
            $rate = $this->postService->getPostUsersRate($post, $user);
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'rate' => $rate,
            'dislike_route' => $router->generate('post_dislike', ['post' => $post->getId()]),
            'like_route' =>  $router->generate('post_like', ['post' => $post->getId()])
        ]);
    }
}
