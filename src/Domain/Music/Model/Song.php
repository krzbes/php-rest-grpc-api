<?php

namespace App\Domain\Music\Model;

use App\Domain\Music\Exception\SongIdAlreadySetException;

class Song
{

    public function __construct(
        private ?int $id,
        private string $title,
        private string $releaseYear,
        private int $authorId
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getReleaseYear(): string
    {
        return $this->releaseYear;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new SongIdAlreadySetException("Song ID is already set.");
        }

        $this->id = $id;
    }
}