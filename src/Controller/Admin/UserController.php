<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\WebController;
use App\Entity\User;
use App\Services\Admin\UserService;
use App\Services\PostService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class UserController extends WebController
{
    /**
     * @var UserService
     */
    private $service;

    public function __construct(
        RequestStack $requestStack,
        UserService $service
    ) {
        parent::__construct($requestStack);
        $this->service = $service;
    }

    public function index(): Response
    {
        return $this->render('admin/user_list.html.twig', [
            'users' => $this->service->getUsers($this->getPage(), $this->request->get('userId')),
        ]);
    }

    /**
     * @IsGranted("BAN", subject="user")
     */
    public function ban(User $user): RedirectResponse
    {
        if ($user->isBanned()) {
            return $this->fallBackWithError('User is banned already');
        }
        $this->service->banUser($user);
        return $this->redirectBackWithSuccess('User is banned');
    }

    public function activate(User $user): RedirectResponse
    {
        if ($user->isActive()) {
            return $this->fallBackWithError('User is already active');
        }
        $this->service->activateUser($user);
        return $this->redirectBackWithSuccess('User is activated');
    }

    public function search(): JsonResponse
    {
        if (is_null($email = $this->request->get('email'))) {
            return $this->json('');
        }
        return $this->json($this->service->search($email));
    }
}
