<?php

namespace App\Infrastructure\Doctrine\Mapper;

use App\Domain\Music\Model\Author as DomainAuthor;
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
            $this->mapDoctrineSongs($source->getSongs())
        );
    }
    private function mapDoctrineSongs(Collection $songs): array
    {
        $result = [];
        foreach ($songs as $song) {
            $result[] = $this->songMapper->toDomain($song);
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
}