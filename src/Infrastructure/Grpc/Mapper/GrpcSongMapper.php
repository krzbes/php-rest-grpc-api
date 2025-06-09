<?php

namespace App\Infrastructure\Grpc\Mapper;

use App\Domain\Music\Model\Song as DomainSong;
use Schema\Song as GrpcSong;

class GrpcSongMapper
{
    public function fromDomain(DomainSong $from):GrpcSong
    {
        $result = new GrpcSong();
        $result->setId($from->getId());
        $result->setTitle($from->getTitle());
        $result->setAuthorId($from->getAuthorId());
        $result->setReleaseYear($from->getReleaseYear());
        return $result;
    }

}