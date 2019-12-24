<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        return in_array($attribute, ['EDIT_POST', 'REMOVE_POST'])
            && $subject instanceof Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Post $subject */
        switch ($attribute) {
            case 'EDIT_POST':
                if (
                        $subject->getAuthor()->getId() === $user->getId() &&
                        $subject->getStatus() === Post::STATUS_DRAFT
                ) {
                    return true;
                }
                break;
            case 'REMOVE_POST':
                if (
                        $subject->getAuthor()->getId() === $user->getId() &&
                        (
                            $subject->getStatus() === Post::STATUS_PUBLISHED ||
                            $subject->getStatus() === Post::STATUS_DECLINED
                        )
                ) {
                    return true;
                }
                break;
        }

        return false;
    }
}
