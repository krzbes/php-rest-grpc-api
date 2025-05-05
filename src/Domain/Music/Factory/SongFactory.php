<?php

namespace App\Domain\Music\Factory;

use App\Domain\Music\Model\Song;

class SongFactory
{
    public function create(string $title, string $releaseYear, int $authorId): Song
    {
        return new Song(id: null, title: $title, releaseYear: $releaseYear, authorId: $authorId);
    }
}