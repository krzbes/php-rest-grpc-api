<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Interfaces\DependencyInjection\Container;
use App\Interfaces\Grpc\AuthorService;
use App\Interfaces\Grpc\SongService;
use Schema\AuthorServiceInterface;
use Schema\SongServiceInterface;
use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;

$container = new Container();

$server = new Server();
$worker = Worker::create();
$server->registerService(SongServiceInterface::class, $container->get(SongService::class));
$server->registerService(AuthorServiceInterface::class, $container->get(AuthorService::class));

$server->serve($worker);