<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Music\Model\Song as DomainSong;
use App\Domain\Music\Repository\SongRepository as SongRepositoryInterface;
use App\Infrastructure\Doctrine\Mapper\SongMapper;
use App\Infrastructure\Doctrine\Model\Song as DoctrineSong;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

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
         FROM App\Infrastructure\Doctrine\Model\Song s 
         LEFT JOIN s.author a'
        );

        foreach ($query->toIterable() as $song) {
            if (!$song instanceof DoctrineSong) {
                continue;
            }

            yield $this->songMapper->toDomain($song);
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(DomainSong $song): void
    {
        if ($song->getId()) {
            $existing = $this->em->find(DoctrineSong::class, $song->getId());

            if (!$existing) {
                throw new \RuntimeException('Song not found for update.');
            }

            $existing->setTitle($song->getTitle());
            $existing->setReleaseYear($song->getReleaseYear());
        } else {
            $new = $this->songMapper->toEntity($song);
            $this->em->persist($new);
        }

        $this->em->flush();
    }

    public function deleteById(string $id): void
    {
        $this->em->createQuery('DELETE FROM App\Infrastructure\Doctrine\Model\Song s WHERE s.id = :id')
            ->setParameter('id', $id)
            ->execute();
    }
}