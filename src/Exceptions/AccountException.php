<?php

namespace App\Exceptions;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

/**
 * Exceptions for users account
 */
class AccountException extends AccountStatusException
{
    public function getMessageKey()
    {
        return $this->getMessage();
    }
}
