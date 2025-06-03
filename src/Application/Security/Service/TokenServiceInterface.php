<?php

namespace App\Application\Security\Service;

use App\Application\Security\Exception\TokenExpiredException;
use App\Domain\Security\Model\AuthenticatedUser;

interface TokenServiceInterface
{
    public function generateToken(AuthenticatedUser $user): string;

    /**
     * @throws TokenExpiredException
     */
    public function parseToken(string $jwt): bool;
}