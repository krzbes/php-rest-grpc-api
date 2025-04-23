<?php

namespace App\Domain\Model;

class Song
{

    public function __construct(
        private int $id,
        private string $title,
        private string $release_year,
        private int $author_id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getReleaseYear(): string
    {
        return $this->release_year;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }
}