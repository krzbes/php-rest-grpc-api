<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Domain\Music\Repository\SongRepository;
use App\Interfaces\DependencyInjection\Container;
use App\Interfaces\Doctrine\Repository\SongRepository as DoctrineSongRepository;
use App\Interfaces\Grpc\AuthorService;
use App\Interfaces\Grpc\SongService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Schema\AuthorServiceInterface;
use Schema\SongServiceInterface;
use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
$container = new Container();
$container->bind(SongRepository::class, DoctrineSongRepository::class);
$container->bind(EntityManagerInterface::class, EntityManager::class);



$entityManager = \App\Interfaces\Doctrine\EntityManagerFactory::create();
$container->set(EntityManager::class, $entityManager);


$server = new Server();
$worker = Worker::create();
$server->registerService(SongServiceInterface::class, $container->get(SongService::class));
$server->registerService(AuthorServiceInterface::class, $container->get(AuthorService::class));

$server->serve($worker);


