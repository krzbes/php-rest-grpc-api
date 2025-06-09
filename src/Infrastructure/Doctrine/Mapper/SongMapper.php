<?php

namespace App\Infrastructure\Doctrine\Mapper;

use App\Domain\Music\Model\Song as DomainSong;
use App\Infrastructure\Doctrine\Model\Song as DoctrineSong;
use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Doctrine\Model\Author as DoctrineAuthor;
use Doctrine\ORM\Exception\ORMException;

class SongMapper
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /**
     * @throws ORMException
     */
    public function toEntity(DomainSong $song): DoctrineSong
    {
        $doctrineSong = new DoctrineSong();
        $doctrineSong->setTitle($song->getTitle());
        $doctrineSong->setReleaseYear($song->getReleaseYear());


        $authorId = $song->getAuthorId();
        $doctrineAuthor = $this->em->getReference(DoctrineAuthor::class, $authorId);

        $doctrineSong->setAuthor($doctrineAuthor);

        return $doctrineSong;
    }

    public function toDomain(DoctrineSong $song): DomainSong
    {
        return new DomainSong($song->getId(),$song->getTitle(),$song->getReleaseYear(), $song->getAuthor()?->getId());
    }
}