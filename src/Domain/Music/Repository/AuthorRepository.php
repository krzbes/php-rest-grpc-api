<?php

namespace App\Domain\Music\Repository;



use App\Domain\Music\Model\Author;

interface AuthorRepository
{
    public function findById(string $id): ?Author;

    public function findAll(): \Generator;

    public function save(Author $author): void;
}