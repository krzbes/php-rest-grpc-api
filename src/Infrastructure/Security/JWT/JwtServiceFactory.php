<?php

namespace App\Infrastructure\Security\JWT;

use RuntimeException;

class JwtServiceFactory
{
    public static function create(): JwtService
    {
        $secret = $_ENV['JWT_SECRET'] ?? null;
        $ttl = $_ENV['JWT_TTL'] ?? null;
        $issuer = $_ENV['APP_NAME'] ?? null;

        if (!$secret) {
            throw new RuntimeException('Missing required env: JWT_SECRET');
        }

        if (!$ttl || !is_numeric($ttl)) {
            throw new RuntimeException('Missing or invalid env: JWT_TTL');
        }

        if (!$issuer) {
            throw new RuntimeException('Missing required env: APP_NAME');
        }

        return new JwtService(
            secret: $secret,
            ttl: (int)$ttl,
            issuer: $issuer
        );
    }
}