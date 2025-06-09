<?php

namespace App\Infrastructure\Http\Handler;

use App\Application\Security\Service\TokenServiceInterface;
use App\Application\Security\UseCase\LoginUseCase;
use App\Domain\Security\Event\LoginFailedException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginHandler implements RequestHandlerInterface
{

    public function __construct(
        private readonly LoginUseCase $loginUseCase,
        private readonly TokenServiceInterface $tokenService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getBody()->getContents();

        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        if (!isset($data['email'], $data['password'])) {
            return new  Response(500, [], 'no login or password');
        }

        try {
            $user = $this->loginUseCase->handle($data['email'], $data['password']);
        } catch (LoginFailedException $exception) {
            return new  Response(500, [], 'failed to login');
        }

        $token = $this->tokenService->generateToken($user);
        return new  Response(200, [], json_encode(['token' => $token], JSON_THROW_ON_ERROR));
    }
}