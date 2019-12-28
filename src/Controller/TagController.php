<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\TagService;
use Symfony\Component\HttpFoundation\JsonResponse;

class TagController extends WebController
{
    public function search(TagService $service): JsonResponse
    {
        if (is_null($tagName = $this->request->get('tag'))) {
            return $this->json('');
        }
        return $this->json($service->search($tagName));
    }
}
