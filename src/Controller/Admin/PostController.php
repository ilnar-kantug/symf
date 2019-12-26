<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\WebController;
use App\Services\Admin\PostService;
use Symfony\Component\HttpFoundation\Response;

class PostController extends WebController
{
    public function index(PostService $service): Response
    {
        return $this->render('admin/post_index.html.twig', [
            'posts' => $service->getNotDraftPosts($this->getPage()),
        ]);
    }
}
