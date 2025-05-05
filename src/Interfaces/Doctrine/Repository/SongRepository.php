<?php

namespace App\Interfaces\Doctrine\Repository;

use App\Domain\Music\Model\Song as DomainSong;
use App\Domain\Music\Repository\SongRepository as SongRepositoryInterface;
use App\Interfaces\Doctrine\Mapper\SongMapper;
use App\Interfaces\Doctrine\Model\Song as DoctrineSong;
use Doctrine\ORM\EntityManagerInterface;

class SongRepository implements SongRepositoryInterface
{
    public function __construct(
        private readonly SongMapper $songMapper,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function findById(string $id): ?DomainSong
    {
        $song = $this->em->getRepository(DoctrineSong::class)->find($id);

        if (!$song instanceof DoctrineSong) {
            return null;
        }

        return $this->songMapper->toDomain($song);
    }

    public function findAll(): \Generator
    {
        $query = $this->em->createQuery(
            'SELECT s, a 
         FROM App\Interfaces\Doctrine\Model\Song s 
         LEFT JOIN s.author a'
        );

        foreach ($query->toIterable() as $song) {
            if (!$song instanceof DoctrineSong) {
                continue;
            }

            yield $this->songMapper->toDomain($song);
        }
    }
}