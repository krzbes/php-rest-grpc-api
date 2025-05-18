<?php

namespace App\Infrastructure\Doctrine\Subscriber;

use App\Domain\Music\Event\SongDeletedEvent;
use App\Infrastructure\Doctrine\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeleteSongSubscriber
{
    public function __construct(private SongRepository $songRepository) {}

    public function __invoke(SongDeletedEvent $event): void
    {
        $this->songRepository->deleteById($event->getSongId());
    }
}