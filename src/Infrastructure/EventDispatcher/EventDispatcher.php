<?php

namespace App\Infrastructure\EventDispatcher;

class EventDispatcher
{
    /**
     * @var array<string, list<callable>>
     */
    private array $listeners = [];

    public function addSubscriber(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function dispatch(object $event): void
    {
        $eventClass = get_class($event);

        foreach ($this->listeners[$eventClass] ?? [] as $listener) {
            $listener($event);
        }
    }
}