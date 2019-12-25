<?php

declare(strict_types=1);

namespace App\DTO;

class UserRegister
{
    private $email;
    private $fullName;
    private $plainPassword;

    public function set(string $email, string $fullName, string $plainPassword): void
    {
        $this->email = $email;
        $this->fullName = $fullName;
        $this->plainPassword = $plainPassword;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
