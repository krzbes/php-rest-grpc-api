<?php

namespace App\Infrastructure\Http\Mapper;

use App\Domain\Music\Model\Author;

class HttpAuthorMapper
{
    public function __construct(private readonly HttpSongMapper $songMapper)
    {
    }

    public function toArray(Author $author): array
    {
        $songs = [];
        foreach ($author->getSongs() as $song) {
            $songs[] = $this->songMapper->toArray($song);
        }

        return [
            'id' => $author->getId(),
            'name' => $author->getName(),
            'surname' => $author->getSurname(),
            'songs' => $songs,
        ];
    }
}