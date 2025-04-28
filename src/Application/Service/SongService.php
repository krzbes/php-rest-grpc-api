<?php

namespace App\Application\Service;

use App\Application\Exception\EntityNotFoundException;
use App\Application\Exception\ValidationException;
use App\Application\Validator\InputValidator;
use App\Domain\Music\Model\Song;
use App\Domain\Music\Repository\SongRepository;
use Spiral\Core\Attribute\Singleton;

class SongService
{
    public function __construct(
        private readonly SongRepository $songRepository,
        private readonly InputValidator $validator
    ) {
    }

    /**
     * @throws ValidationException
     * @throws EntityNotFoundException
     */
    public function fetchSong(int $id): Song
    {
        $this->validator->validateId($id);

        $song = $this->songRepository->findById($id);

        if ($song === null) {
            throw new EntityNotFoundException('Song not found.');
        }
        return $song;
    }
}