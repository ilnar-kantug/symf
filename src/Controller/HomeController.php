<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\HomeService;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     */
    public function index(HomeService $service)
    {
        return $this->render('home/index.html.twig', [
            'posts' => $service->getPublishedPosts($this->getPage()),
        ]);
    }
}
