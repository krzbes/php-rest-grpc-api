<?php

namespace App\Infrastructure\Security\JWT;

use App\Application\Security\Exception\TokenExpiredException;
use App\Application\Security\Service\TokenServiceInterface;
use App\Domain\Security\Model\AuthenticatedUser;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService implements TokenServiceInterface
{
    public function __construct(
        private string $secret,
        private int $ttl,
        private string $issuer
    ) {
    }

    public function generateToken(AuthenticatedUser $user): string
    {
        $payload = [
            'name' => $user->getUsername(),
            'iss' => $this->issuer,
            'iat' => time(),
            'exp' => time() + $this->ttl
        ];
        return JWT::encode($payload, $this->secret, 'HS256');
    }


    /**
     * @inheritDoc
     */
    public function parseToken(string $jwt): bool
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret, 'HS256'));

            return true;
        } catch (ExpiredException $e) {
            throw new TokenExpiredException('Refresh token');
        } catch(\Exception $e) {
            return false;
        }
    }
}