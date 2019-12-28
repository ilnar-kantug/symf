<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\TagRepository;

class TagService
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getTagsByIds($requestedTags): array
    {
        $tags = [];
        if (!empty($requestedTags)) {
            $tags = $this->tagRepository->findManyById($requestedTags);
        }
        return $tags;
    }

    public function search(string $tagName): array
    {
        return $this->tagRepository->searchLikeByName($tagName);
    }
}
