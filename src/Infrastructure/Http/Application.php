<?php

namespace App\Infrastructure\Http;


use App\Infrastructure\DependencyInjection\CommonContainerFactory;
use App\Infrastructure\DependencyInjection\Container;
use App\Infrastructure\Http\Router\Router;
use App\Infrastructure\Http\Router\RouterFactory;
use Dotenv\Dotenv;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Application
{
    private Container $container;
    private Router $router;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

        $this->container = CommonContainerFactory::create();
        $this->container->bindFactory(Router::class, fn($c) => RouterFactory::create($c));
        $this->router = $this->container->get(Router::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->router->handle($request);
    }

}