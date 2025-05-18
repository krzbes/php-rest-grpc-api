<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Domain\Music\Repository\SongRepository;
use App\Infrastructure\DependencyInjection\Container;
use App\Infrastructure\Doctrine\EntityManagerFactory;
use App\Infrastructure\Doctrine\Repository\SongRepository as DoctrineSongRepository;
use App\Infrastructure\EventDispatcher\EventDispatcher;
use App\Infrastructure\EventDispatcher\EventDispatcherFactory;
use App\Infrastructure\Grpc\AuthorService;
use App\Infrastructure\Grpc\SongService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Schema\AuthorServiceInterface;
use Schema\SongServiceInterface;
use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();
$container = new Container();
$container->bind(SongRepository::class, DoctrineSongRepository::class);
$container->bind(EntityManagerInterface::class, EntityManager::class);


$validator = Validation::createValidator();
$container->set(ValidatorInterface::class, $validator);

$container->bindFactory(EventDispatcher::class, fn($c) => EventDispatcherFactory::create($c));
$container->bindFactory(EntityManager::class, fn() => EntityManagerFactory::create());


$server = new Server();
$worker = Worker::create();
$server->registerService(SongServiceInterface::class, $container->get(SongService::class));
$server->registerService(AuthorServiceInterface::class, $container->get(AuthorService::class));

$server->serve($worker);


