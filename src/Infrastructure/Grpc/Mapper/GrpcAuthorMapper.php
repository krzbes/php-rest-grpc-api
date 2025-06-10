<?php

declare(strict_types=1);

namespace App\Infrastructure\Grpc\Mapper;


use App\Domain\Music\Model\Author as DomainAuthor;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Schema\Author as GrpcAuthor;
use Schema\Song as GrpcSong;

class GrpcAuthorMapper
{
    public function __construct(private readonly GrpcSongMapper $songMapper)
    {
    }

    public function fromDomain(DomainAuthor $author): GrpcAuthor
    {
        $result = new GrpcAuthor();
        $result->setId((int) $author->getId());
        $result->setName($author->getName());
        $result->setSurname($author->getSurname());


        $songs = new RepeatedField(GPBType::MESSAGE, GrpcSong::class);
        foreach ($author->getSongs() as $song) {
            $songs[] = $this->songMapper->fromDomain($song);
        }
        $result->setSongs($songs);
        return $result;
    }
}
