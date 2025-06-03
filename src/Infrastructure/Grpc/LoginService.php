<?php

namespace App\Infrastructure\Grpc;

use App\Application\Security\Service\TokenServiceInterface;
use App\Application\Security\UseCase\LoginUseCase;
use App\Domain\Security\Event\LoginFailedException;
use App\Infrastructure\Grpc\Authentication\JwtAuthenticator;
use Schema\AuthServiceInterface;
use Schema\LoginRequest;
use Schema\LoginResponse;
use Spiral\RoadRunner\GRPC;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\StatusCode;

class LoginService implements AuthServiceInterface
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase,
        private readonly TokenServiceInterface $tokenService
    ) {
    }

    public function Login(GRPC\ContextInterface $ctx, LoginRequest $in): LoginResponse
    {

        $email = $in->getEmail();
        if (!$email) {
            throw new GRPCException('No email provided', StatusCode::INVALID_ARGUMENT);
        }

        $password = $in->getPassword();
        if (!$password) {
            throw new GRPCException('No password provided', StatusCode::INVALID_ARGUMENT);
        }
        try {
            $user = $this->loginUseCase->handle($email, $password);
        } catch (LoginFailedException $e) {
            throw new GRPCException('Failed to login', StatusCode::UNAUTHENTICATED);
        }
        $token = $this->tokenService->generateToken($user);

        $response = new LoginResponse();
        $response->setToken($token);
        return $response;
    }
}