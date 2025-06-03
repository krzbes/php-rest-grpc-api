<?php

namespace App\Domain\Security\Repository;

use App\Domain\Security\Model\AuthenticatedUser;

interface AuthenticatedUserRepository
{
    public function getByEmail(string $email): ?AuthenticatedUser;
}