<?php

namespace App\Domain\Repository;

use App\Domain\Model\Song;

interface SongRepository
{
    public function findById(string $id): ?Song;
}