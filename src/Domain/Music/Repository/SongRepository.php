<?php

namespace App\Domain\Music\Repository;

use App\Domain\Music\Model\Song;

interface SongRepository
{
    public function findById(string $id): ?Song;
}