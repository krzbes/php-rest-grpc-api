<?php

namespace App\Infrastructure\Doctrine\Subscriber;

use App\Domain\Music\Event\AuthorDeletedEvent;
use App\Infrastructure\Doctrine\Repository\AuthorRepository;


class DeleteAuthorSubscriber
{
    public function __construct(private readonly AuthorRepository $repository)
    {
    }

    public function __invoke(AuthorDeletedEvent $event): void
    {
        $this->repository->deleteById($event->getAuthorId());
    }
}