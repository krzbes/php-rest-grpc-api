<?php

namespace App\Infrastructure\Security\Password;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory as SymfonyPasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherFactory
{
    public static function create(): UserPasswordHasherInterface
    {
        $factory = new SymfonyPasswordHasherFactory([
            SymfonyAuthUser::class => ['algorithm' => 'bcrypt']
        ]);
        return new UserPasswordHasher($factory);
    }
}