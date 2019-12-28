<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OldValueExtension extends AbstractExtension
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(
        RequestStack $requestStack
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('old', [$this, 'getOldValue']),
        ];
    }

    public function getOldValue(string $inputName)
    {
        return $this->request->get($inputName);
    }
}
