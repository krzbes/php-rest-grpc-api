<?php

namespace App\Infrastructure\Grpc\Authentication;

use App\Application\Security\Exception\TokenExpiredException;
use App\Application\Security\Service\TokenServiceInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\StatusCode;

class JwtAuthenticator
{
    public function __construct(private readonly TokenServiceInterface $tokenService)
    {
    }

    public function authenticate(ContextInterface $context): void
    {
        $authHeader = $context->getValue('authorization')[0] ?? null;

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new GRPCException('Unauthorized: missing or malformed token', StatusCode::UNAUTHENTICATED);
        }

        $jwt = substr($authHeader, 7);

        try {
            if (!$this->tokenService->parseToken($jwt)) {
                throw new GRPCException('Token verification failed', StatusCode::UNAUTHENTICATED);
            }
        } catch (TokenExpiredException $e) {
            throw new GRPCException('Refresh token', StatusCode::RESOURCE_EXHAUSTED);
        }
    }
}