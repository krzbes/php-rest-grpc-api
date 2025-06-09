<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Music\Model\Author as DomainAuthor;
use App\Infrastructure\Doctrine\Model\Author as DoctrineAuthor;
use App\Domain\Music\Repository\AuthorRepository as AuthorRepositoryInterface;
use App\Infrastructure\Doctrine\Mapper\AuthorMapper;

use Doctrine\ORM\EntityManagerInterface;

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
        // TODO: Implement findAll() method.
    }

    public function save(DomainAuthor $author): void
    {
        // TODO: Implement save() method.
    }
}