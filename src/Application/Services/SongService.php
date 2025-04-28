<?php

namespace App\Application\Services;

use App\Domain\Music\Model\Song;
use App\Domain\Music\Repository\SongRepository;

class SongService
{
    public function __construct(private readonly SongRepository $songRepository)
    {
    }

    public function fetchSong(int $id): ?Song
    {
        return $this->songRepository->findById($id);
    }
}