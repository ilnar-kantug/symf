<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\ProfileService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends WebController
{
    /**
     * @Route("/profile", name="profile")
     * @IsGranted("ROLE_USER")
     */
    public function index(ProfileService $service)
    {
        $user = $this->getUser();

        $posts = $service->getUserPost($this->getPage(), $user->getId());

        return $this->render('profile/index.html.twig', [
            'posts' => $posts,
            'user' => $user,
            'rating' => $service->getUserRating($user->getId())
        ]);
    }
}
