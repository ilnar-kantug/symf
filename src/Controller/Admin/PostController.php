<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\WebController;
use App\Entity\Post;
use App\DTO\Filters\Post as PostFilterDto;
use App\Services\Admin\PostService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class PostController extends WebController
{
    public function index(PostService $service, PostFilterDto $postFilterDto): Response
    {
        $postFilterDto->set(
            $this->request->get('userId'),
            $this->request->get('status'),
            $this->request->get('dateFrom'),
            $this->request->get('dateTo'),
        );

        return $this->render('admin/post_index.html.twig', [
            'posts' => $service->getNotDraftPosts($this->getPage(), $postFilterDto),
            'statuses' => Post::STATUSES
        ]);
    }

    public function edit(Post $post): Response
    {
        return $this->render('admin/post_edit.html.twig', [
            'post' => $post,
            'statuses' => Post::STATUSES
        ]);
    }

    public function update(Post $post, PostService $service): RedirectResponse
    {
        if (! in_array($status = (int) $this->request->get('status'), Post::STATUSES)) {
            return $this->fallBackWithError('Status is invalid');
        }
        $service->updatePost($post, $status);
        return $this->redirectBackWithSuccess('Post status changed successfully');
    }
}
