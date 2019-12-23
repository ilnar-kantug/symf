<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostRatingVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, ['RATE'])
            && $subject instanceof Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Post $subject */
        if ($subject->getAuthor()->getId() !== $user->getId()) {
            return true;
        }

        return false;
    }
}
