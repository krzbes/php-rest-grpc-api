<?php

namespace App\Infrastructure\Http\Router;

use App\Infrastructure\DependencyInjection\Container;
use App\Infrastructure\Http\Controller\SongController;
use App\Infrastructure\Http\Handler\ControllerMethodHandler;
use App\Infrastructure\Http\Handler\LoginHandler;
use App\Infrastructure\Http\Middleware\AuthMiddleware;

class RouterFactory
{
    public static function create(Container $container): Router
    {
        $router = new Router();
        $router->addRoute('POST', '/login', $container->get(LoginHandler::class));
        $router->addRoute(
            'GET',
            '/song',
            new ControllerMethodHandler([$container->get(SongController::class), 'getSong']),
            [$container->get(AuthMiddleware::class)]
        );
        return $router;
    }
}