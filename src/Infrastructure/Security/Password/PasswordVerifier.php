<?php

namespace App\Infrastructure\Security\Password;

use App\Application\Security\Service\PasswordVerifierInterface;
use App\Domain\Security\Model\AuthenticatedUser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordVerifier implements PasswordVerifierInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function isPasswordValid(AuthenticatedUser $user, string $plainPassword): bool
    {
        $symfonyUser = new SymfonyAuthUser($user);
        return $this->passwordHasher->isPasswordValid($symfonyUser, $plainPassword);
    }
}