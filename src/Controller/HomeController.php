<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\HomeService;

class HomeController extends WebController
{
    public function index(HomeService $service)
    {
        return $this->render('home/index.html.twig', [
            'posts' => $service->getPublishedPosts($this->getPage()),
        ]);
    }
}
