<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\Paginator;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends BaseController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, UserRepository $userRepository)
    {
        $user = $this->getUser();
        $posts = $paginator->paginate(
            $postRepository->getQueryForAuthUserPosts($user->getId()),
            $this->getPage(),
            Paginator::PROFILE_PER_PAGE
        );

        return $this->render('profile/index.html.twig', [
            'posts' => $posts,
            'user' => $user,
            'rating' => $userRepository->getUserRating($user->getId())
        ]);
    }
}
