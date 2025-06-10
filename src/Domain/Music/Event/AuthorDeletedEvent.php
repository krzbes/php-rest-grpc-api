<?php

namespace App\Domain\Music\Event;

use App\Domain\Common\DomainEvent;

class AuthorDeletedEvent implements DomainEvent
{

    private \DateTimeImmutable $occurredAt;

    public function __construct(private readonly string $authorId)
    {
        $this->occurredAt = new \DateTimeImmutable();
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