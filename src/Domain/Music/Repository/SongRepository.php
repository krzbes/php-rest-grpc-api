<?php

namespace App\Domain\Music\Repository;

use App\Domain\Music\Model\Song;

interface SongRepository
{
    public function findById(string $id): ?Song;

    public function findAll(): \Generator;

    public function save(Song $song): void;
}