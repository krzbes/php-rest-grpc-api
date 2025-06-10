<?php

namespace App\Domain\Music\Model;

use App\Domain\Common\DomainEvent;
use App\Domain\Music\Event\AuthorDeletedEvent;
use App\Domain\Music\Event\SongAddedToAuthorEvent;
use App\Domain\Music\Event\SongDeletedEvent;
use App\Domain\Music\Event\SongDeletedFromAuthorEvent;
use App\Domain\Music\Exception\AuthorIdAlreadySetException;

class Author
{
    private array $events = [];

    public function __construct(
        private ?int $id,
        private string $name,
        private string $surname,
        private array $songs,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getSongs(): array
    {
        return $this->songs;
    }

    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new AuthorIdAlreadySetException("Author ID is already set.");
        }

        $this->id = $id;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function changeSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    private function addSong(Song $song): void
    {
        $this->songs[] = $song;
        $this->addEvent(new SongAddedToAuthorEvent($this->id, $song->getId()));
    }

    private function deleteSong(Song $song): void
    {
        $this->songs = array_filter($this->songs, static function (Song $listedSong) use ($song) {
            return $listedSong->getId() !== $song->getId();
        });
        $this->addEvent(new SongDeletedFromAuthorEvent($this->id, $song->getId()));
    }

    public function delete(): void
    {
        $this->addEvent(new AuthorDeletedEvent($this->id));
    }

    private function addEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}