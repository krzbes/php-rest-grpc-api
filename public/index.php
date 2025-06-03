<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Application\Security\Service\PasswordVerifierInterface;
use App\Application\Security\Service\TokenServiceInterface;
use App\Domain\Music\Repository\SongRepository;
use App\Domain\Security\Repository\AuthenticatedUserRepository;
use App\Infrastructure\DependencyInjection\Container;
use App\Infrastructure\Doctrine\EntityManagerFactory;
use App\Infrastructure\Doctrine\Repository\SongRepository as DoctrineSongRepository;
use App\Infrastructure\Doctrine\Repository\AuthenticatedUserRepository as DoctrineAuthenticatedUserRepository;
use App\Infrastructure\EventDispatcher\EventDispatcher;
use App\Infrastructure\EventDispatcher\EventDispatcherFactory;
use App\Infrastructure\Grpc\AuthorService;
use App\Infrastructure\Grpc\LoginService;
use App\Infrastructure\Grpc\SongService;
use App\Infrastructure\Security\JWT\JwtServiceFactory;
use App\Infrastructure\Security\Password\PasswordHasherFactory;
use App\Infrastructure\Security\Password\PasswordVerifier;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Schema\AuthorServiceInterface;
use Schema\AuthServiceInterface;
use Schema\SongServiceInterface;
use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$container = new Container();
$container->bind(SongRepository::class, DoctrineSongRepository::class);
$container->bind(AuthenticatedUserRepository::class, DoctrineAuthenticatedUserRepository::class);
$container->bind(EntityManagerInterface::class, EntityManager::class);


$validator = Validation::createValidator();
$container->set(ValidatorInterface::class, $validator);

$container->bindFactory(EventDispatcher::class, fn($c) => EventDispatcherFactory::create($c));
$container->bindFactory(EntityManager::class, fn() => EntityManagerFactory::create());


$container->bindFactory(UserPasswordHasherInterface::class, fn() => PasswordHasherFactory::create());
$container->bind(PasswordVerifierInterface::class, PasswordVerifier::class);

$container->bindFactory(TokenServiceInterface::class, fn() => JwtServiceFactory::create());

$server = new Server();
$worker = Worker::create();

$server->registerService(AuthServiceInterface::class, $container->get(LoginService::class));
$server->registerService(SongServiceInterface::class, $container->get(SongService::class));
$server->registerService(AuthorServiceInterface::class, $container->get(AuthorService::class));

$server->serve($worker);


