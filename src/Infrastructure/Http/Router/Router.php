<?php

namespace App\Infrastructure\Http\Router;

use App\Infrastructure\Http\Middleware\MiddlewareHandler;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router
{
    private array $routes = [];

    public function addRoute(
        string $method,
        string $path,
        RequestHandlerInterface $handler,
        array $middleware = []
    ): void {
        $this->routes[strtoupper($method)][$path] = [
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $method = strtoupper($request->getMethod());
        $path = $request->getUri()->getPath();

        if (!isset($this->routes[$method][$path])) {
            return new Response(404, [], 'Not Found');
        }

        $route = $this->routes[$method][$path];
        $controller = $route['handler'];
        $middlewareStack = $route['middleware'];

        return $this->buildMiddlewarePipeline($middlewareStack, $controller)->handle($request);
    }

    private function buildMiddlewarePipeline(
        array $middlewareStack,
        RequestHandlerInterface $finalHandler
    ): RequestHandlerInterface {
        return array_reduce(
            array_reverse($middlewareStack),
            static fn(RequestHandlerInterface $next, MiddlewareInterface $middleware) => new MiddlewareHandler(
                $middleware, $next
            ),
            $finalHandler
        );
    }
}