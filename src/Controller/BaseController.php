<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseController extends AbstractController
{
    protected const SUCCESS_MSG = 'Success!';
    protected const ERROR_MSG = 'Something went wrong!';

    /**
     * @var Request
     */
    protected $request;
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->em = $em;
    }

    protected function getPage(): int
    {
        return (int) $this->request->get(Paginator::PAGE) ?: Paginator::DEFAULT_PAGE;
    }

    protected function supposeBackUrl(): string
    {
        return $this->request->headers->get('referer') ?: '/';
    }

    protected function redirectBack(): RedirectResponse
    {
        return $this->redirect($this->supposeBackUrl());
    }

    protected function fallToRouteWithError(string $message = self::ERROR_MSG, string $route = 'app_login'): RedirectResponse
    {
        return $this->fallWithError($message, $this->generateUrl($route));
    }

    protected function fallBackWithError(string $message = self::ERROR_MSG): RedirectResponse
    {
        return $this->fallWithError($message, $this->supposeBackUrl());
    }

    protected function fallWithError(string $message = self::ERROR_MSG, string $url = '/'): RedirectResponse
    {
        $this->addFlash('error', $message);
        return $this->redirect($url);
    }

    protected function redirectBackWithSuccess(string $message = self::SUCCESS_MSG): RedirectResponse
    {
        return $this->redirectWithSuccess($message, $this->supposeBackUrl());
    }

    protected function redirectToRouteWithSuccess(string $message = self::SUCCESS_MSG, string $route = 'app_login'): RedirectResponse
    {
        return $this->redirectWithSuccess($message, $this->generateUrl($route));
    }

    protected function redirectWithSuccess(string $message = self::SUCCESS_MSG, string $url = '/'): RedirectResponse
    {
        $this->addFlash('success', $message);
        return $this->redirect($url);
    }
}
