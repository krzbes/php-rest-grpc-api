<?php

namespace App\Interfaces\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;

class EntityManagerFactory
{
    public static function create(): EntityManagerInterface
    {
        $dbParams = [
            'driver' => $_ENV['DB_DRIVER'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'dbname' => $_ENV['DB_NAME'],
            'host' => $_ENV['DB_HOST'],
        ];
        $config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/Model'],
            true,
            __DIR__ . '/../../../var/cache/proxies');
        $connection = DriverManager::getConnection($dbParams, $config);
        return new EntityManager($connection, $config);
    }
}

