<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BaseController extends AbstractController
{
    protected function fallWithError($message = 'Something went wrong.', $route = 'app_login'): RedirectResponse
    {
        $this->addFlash('error', $message);
        return $this->redirectToRoute($route);
    }
}