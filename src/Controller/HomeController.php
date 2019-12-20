<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\Paginator;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BaseController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $postRepository, PaginatorInterface $paginator)
    {
        $posts = $paginator->paginate(
            $postRepository->getQueryForAllPublished(),
            $this->getPage(),
            Paginator::PER_PAGE
        );

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
