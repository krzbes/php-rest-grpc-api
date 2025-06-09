<?php

namespace App\Infrastructure\Http\Router;

use App\Infrastructure\DependencyInjection\Container;
use App\Infrastructure\Http\Controller\AuthorController;
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
        $router->addRoute(
            'PUT',
            '/song',
            new ControllerMethodHandler([$container->get(SongController::class), 'createSong']),
            [$container->get(AuthMiddleware::class)]
        );
        $router->addRoute(
            'DELETE',
            '/song',
            new ControllerMethodHandler([$container->get(SongController::class), 'deleteSong']),
            [$container->get(AuthMiddleware::class)]
        );
        $router->addRoute(
            'GET',
            '/songs',
            new ControllerMethodHandler([$container->get(SongController::class), 'listSongs']),
            [$container->get(AuthMiddleware::class)]
        );
        $router->addRoute(
            'GET',
            '/author',
            new ControllerMethodHandler([$container->get(AuthorController::class), 'getAuthor']),
            [$container->get(AuthMiddleware::class)]
        );

        return $router;
    }
}