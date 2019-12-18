<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function index(int $id, PostRepository $postRepository)
    {
        return $this->render('post/show.html.twig', [
            'post' => $postRepository->getPostWithComments($id),
        ]);
    }
}
