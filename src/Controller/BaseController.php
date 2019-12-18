<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseController extends AbstractController
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function fallWithError($message = 'Something went wrong.', $route = 'app_login'): RedirectResponse
    {
        $this->addFlash('error', $message);
        return $this->redirectToRoute($route);
    }

    protected function getPage(): int
    {
       return $this->request->get(Paginator::PAGE) ?: Paginator::DEFAULT_PAGE;
    }
}