<?php

namespace App\Infrastructure\Http\Mapper;

use App\Domain\Music\Model\Song;

class HttpSongMapper
{
    public function toArray(Song $song): array
    {
        return [
            'id' => $song->getId(),
            'title' => $song->getTitle(),
            'releaseYear' => $song->getReleaseYear(),
            'authorId' => $song->getAuthorId()
        ];
    }
}