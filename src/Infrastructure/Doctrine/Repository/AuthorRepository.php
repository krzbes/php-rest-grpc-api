<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Music\Model\Author as DomainAuthor;
use App\Infrastructure\Doctrine\Model\Author as DoctrineAuthor;
use App\Domain\Music\Repository\AuthorRepository as AuthorRepositoryInterface;
use App\Infrastructure\Doctrine\Mapper\AuthorMapper;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function __construct(
        private readonly AuthorMapper $authorMapper,
        private readonly EntityManagerInterface $em
    ) {
    }

    public function findById(string $id): ?DomainAuthor
    {
        $author = $this->em->getRepository(DoctrineAuthor::class)->find($id);

        if (!$author instanceof DoctrineAuthor) {
            return null;
        }

        return $this->authorMapper->toDomain($author);
    }

    public function findAll(): \Generator
    {
        /** @var Connection $conn */
        $conn = $this->em->getConnection();
        $sql = '
        SELECT 
            a.id    AS author_id,
            a.name  AS author_name,
            a.surname AS author_surname,
            s.id    AS song_id,
            s.title AS song_title,
            s.release_year AS song_releaseYear
        FROM author a
        LEFT JOIN song s ON s.author_id = a.id
        ORDER BY a.id
    ';
        $stmt = $conn->executeQuery($sql);

        $currentId = null;
        $authorBuffer = null;

        while ($row = $stmt->fetchAssociative()) {
            if ($row['author_id'] !== $currentId) {
                if ($authorBuffer !== null) {
                    yield $this->authorMapper->mapFromArrayToDomain($authorBuffer);
                }
                $currentId = $row['author_id'];
                $authorBuffer = [
                    'id' => $row['author_id'],
                    'name' => $row['author_name'],
                    'surname' => $row['author_surname'],
                    'songs' => [],
                ];
            }

            if ($row['song_id'] !== null) {
                $authorBuffer['songs'][] = [
                    'id' => $row['song_id'],
                    'title' => $row['song_title'],
                    'releaseYear' => $row['song_releaseYear'],
                ];
            }
        }

        if ($authorBuffer !== null) {
            yield $this->authorMapper->mapFromArrayToDomain($authorBuffer);
        }
    }


    public function save(DomainAuthor $author): void
    {
        if ($author->getId() !== null) {
            $existing = $this->em->find(DoctrineAuthor::class, $author->getId());
            if (!$existing) {
                throw new \RuntimeException('Author not found for update.');
            }

            $existing->setName($author->getName());
            $existing->setSurname($author->getSurname());
        } else {
            $new = $this->authorMapper->toEntity($author);
            $this->em->persist($new);
        }
        $this->em->flush();
    }

    public function deleteById(int $id): void
    {
        $this->em->createQuery('DELETE FROM App\Infrastructure\Doctrine\Model\Song s where s.author_id = :id')
            ->setParameter('id', $id)
            ->execute();
        $this->em->createQuery('DELETE FROM App\Infrastructure\Doctrine\Model\Author a WHERE a.id = :id')
            ->setParameter('id', $id)
            ->execute();
    }
}