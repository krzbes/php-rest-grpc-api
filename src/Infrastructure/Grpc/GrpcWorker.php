<?php


require_once dirname(__DIR__) . '../../../vendor/autoload.php';


use App\Infrastructure\DependencyInjection\CommonContainerFactory;
use App\Infrastructure\Grpc\Service\AuthorService;
use App\Infrastructure\Grpc\Service\LoginService;
use App\Infrastructure\Grpc\Service\SongService;
use Schema\AuthorServiceInterface;
use Schema\AuthServiceInterface;
use Schema\SongServiceInterface;
use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

$container = CommonContainerFactory::create();

$server = new Server();
$worker = Worker::create();

$server->registerService(AuthServiceInterface::class, $container->get(LoginService::class));
$server->registerService(SongServiceInterface::class, $container->get(SongService::class));
$server->registerService(AuthorServiceInterface::class, $container->get(AuthorService::class));

$server->serve($worker);


