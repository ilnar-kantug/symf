<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\HomeService;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends WebController
{
    public function index(HomeService $service): Response
    {
        return $this->render('home/index.html.twig', [
            'posts' => $service->getPublishedPosts($this->getPage()),
            'topPosts' => $service->getTopRatedPosts(),
            'topAuthors' => $service->getTopRatedAuthors(),
        ]);
    }
}
