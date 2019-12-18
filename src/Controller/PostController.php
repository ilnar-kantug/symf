<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{id}", name="post_show")
     */
    public function index(Post $post)
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
