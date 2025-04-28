<?php

namespace App\Interfaces\Doctrine\Mapper;

use App\Domain\Music\Model\Song as DomainSong;
use App\Interfaces\Doctrine\Model\Song as DoctrineSong;

class SongMapper
{
    public function toEntity(DomainSong $song): DoctrineSong
    {
        return new DoctrineSong();
    }

    public function toDomain(DoctrineSong $song): DomainSong
    {
        return new DomainSong($song->getId(),$song->getTitle(),$song->getReleaseYear(), $song->getAuthor()->getId());
    }
}