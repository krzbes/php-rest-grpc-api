<?php

namespace App\Interfaces\Doctrine\Repository;

use App\Domain\Model\Song;
use App\Domain\Repository\SongRepository as SongRepositoryInterface;

class SongRepository implements SongRepositoryInterface
{
    public function __construct()
    {
    }

    public function findById(string $id): ?Song
    {
        // TODO: Implement findById() method.
    }
}