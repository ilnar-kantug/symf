<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\WebController;
use App\Services\Admin\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends WebController
{
    public function index(UserService $service): Response
    {
        return $this->render('admin/user_list.html.twig', [
            'users' => $service->getUsers($this->getPage()),
        ]);
    }
}
