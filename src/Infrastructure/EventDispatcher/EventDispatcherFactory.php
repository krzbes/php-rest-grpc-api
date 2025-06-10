<?php

namespace App\Infrastructure\EventDispatcher;

use App\Domain\Music\Event\SongDeletedEvent;
use App\Infrastructure\DependencyInjection\Container;
use App\Infrastructure\Doctrine\Subscriber\DeleteAuthorSubscriber;
use App\Infrastructure\Doctrine\Subscriber\DeleteSongSubscriber;

class EventDispatcherFactory
{
    public static function create(Container $container): EventDispatcher
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(
            SongDeletedEvent::class,
            [$container->get(DeleteSongSubscriber::class), '__invoke']
        );

        $dispatcher->addSubscriber(
            DeleteAuthorSubscriber::class,
            [$container->get(DeleteAuthorSubscriber::class), '__invoke']
        );
        return $dispatcher;
    }
}