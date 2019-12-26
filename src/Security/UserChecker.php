<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Exceptions\AccountException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $identity): void
    {
        if (!$identity instanceof User) {
            return;
        }

        if ($identity->isNotConfirmed()) {
            $exception = new AccountException('User account is not confirmed.');
            $exception->setUser($identity);
            throw $exception;
        }

        if ($identity->isBanned()) {
            $exception = new AccountException('Account is banned.');
            $exception->setUser($identity);
            throw $exception;
        }
    }

    public function checkPostAuth(UserInterface $identity): void
    {
        if (!$identity instanceof User) {
            return;
        }
    }
}
