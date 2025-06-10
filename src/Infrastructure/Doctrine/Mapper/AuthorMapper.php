<?php

namespace App\Infrastructure\Doctrine\Mapper;

use App\Domain\Music\Model\Author as DomainAuthor;
use App\Infrastructure\Doctrine\Exception\ArrayMappingException;
use App\Infrastructure\Doctrine\Model\Author as DoctrineAuthor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class AuthorMapper
{
    public function __construct(private readonly SongMapper $songMapper)
    {
    }

    public function toEntity(DomainAuthor $source): DoctrineAuthor
    {
        $author = new DoctrineAuthor();
        $author->setId($source->getId());
        $author->setName($source->getName());
        $author->setSurname($source->getSurname());
        $author->setSongs($this->mapDomainSongs($source->getSongs()));
        return $author;
    }

    public function toDomain(DoctrineAuthor $source): DomainAuthor
    {
        return new DomainAuthor(
            $source->getId(),
            $source->getName(),
            $source->getSurname(),
            $this->mapDoctrineSongs($source->getSongs(), $source->getId())
        );
    }

    public function mapFromArrayToDomain(array $data): DomainAuthor
    {
        if (!isset(
            $data['id'], $data['name'],
            $data['surname'], $data['songs']
        )) {
            throw new ArrayMappingException('not enough data');
        }

        return new DomainAuthor(
            $data['id'],
            $data['name'],
            $data['surname'],
            $this->mapArraySongs($data['songs'], $data['id']),
        );
    }

    private function mapDoctrineSongs(Collection $songs, int $authorId): array
    {
        $result = [];
        foreach ($songs as $song) {
            $result[] = $this->songMapper->toDomain($song, $authorId);
        }
        return $result;
    }

    private function mapDomainSongs(array $songs): Collection
    {
        $result = [];
        foreach ($songs as $song) {
            $result[] = $this->songMapper->toEntity($song);
        }
        return new ArrayCollection($result);
    }

    private function mapArraySongs(array $songs, int $authorId): array
    {
        $result = [];
        foreach ($songs as $song) {
            $result[] = $this->songMapper->mapFormArrayToDomain($song, $authorId);
        }
        return $result;
    }
}