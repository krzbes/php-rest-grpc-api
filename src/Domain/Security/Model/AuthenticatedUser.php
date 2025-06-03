<?php

namespace App\Domain\Security\Model;

class AuthenticatedUser
{
    public function __construct(private string $username, private string $password)
    {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}