<?php

namespace App\Interfaces\Doctrine\Mapper;

use App\Domain\Model\Song;
use App\Interfaces\Doctrine\Model\Song as SongEntity;

class SongMapper
{
    public function toEntity(): SongEntity
    {
    }

    public function toDomain(SongEntity $song): Song
    {
        return new Song($song->getId(),$song->getTitle(),$song->getReleaseYear(), $song->);
    }
}