<?php

namespace App\Infrastructure\Security\Password;


use App\Domain\Security\Model\AuthenticatedUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


class SymfonyAuthUser implements PasswordAuthenticatedUserInterface
{
    public function __construct(private readonly AuthenticatedUser $authUser )
    {
    }

    public function getPassword(): ?string
    {
        return $this->authUser->getPassword();
    }
}