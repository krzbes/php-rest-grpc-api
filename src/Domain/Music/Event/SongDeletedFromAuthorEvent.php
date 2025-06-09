<?php

namespace App\Domain\Music\Event;

use App\Domain\Common\DomainEvent;

class SongDeletedFromAuthorEvent implements DomainEvent
{

    private \DateTimeImmutable $occurredAt;

    public function __construct(private readonly string $authorId, private readonly string $songId)
    {
        $this->occurredAt = new \DateTimeImmutable();
    }

    public function getSongId(): string
    {
        return $this->songId;
    }
    public function getAuthorId(): string
    {
        return $this->authorId;
    }
    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }


}