<?php

namespace App\Application\Security\Service;

use App\Domain\Security\Model\AuthenticatedUser;

interface PasswordVerifierInterface
{
    public function isPasswordValid(AuthenticatedUser $user, string $plainPassword): bool;
}