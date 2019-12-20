<?php

declare(strict_types=1);

namespace App\Twig;

use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RatingExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('count_rating', [$this, 'countRating']),
        ];
    }

    public function countRating(PersistentCollection $postRatings): int
    {
        if ($postRatings->count() === 0) {
            return 0;
        }
        $rating = 0;
        foreach ($postRatings as $postRating) {
            $rating += $postRating->getScore();
        }
        return $rating;
    }
}
