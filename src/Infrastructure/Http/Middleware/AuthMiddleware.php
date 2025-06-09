<?php

namespace App\Infrastructure\Http\Middleware;

use App\Application\Security\Exception\TokenExpiredException;
use App\Application\Security\Service\TokenServiceInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly TokenServiceInterface $tokenService)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine('Authorization');


        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return new Response(401, [], 'Missing or invalid Authorization header');
        }

        $token = trim(substr($header, 7));

        try {
             if(!$this->tokenService->parseToken($token)){
                 return new Response(401, [], 'Token verification failed');
             }
        } catch (TokenExpiredException $e){
            return new Response(401, [], 'Refresh token');
        }

        return $handler->handle($request);
    }
}