<?php

namespace App\Application\Service;

use App\Application\Exception\EntityNotFoundException;
use App\Application\Exception\FailedToSaveSongException;
use App\Application\Exception\ValidationException;
use App\Application\Validator\InputValidator;
use App\Domain\Music\Factory\SongFactory;
use App\Domain\Music\Model\Song;
use App\Domain\Music\Repository\SongRepository;
use Spiral\Core\Attribute\Singleton;

class SongService
{
    public function __construct(
        private readonly SongRepository $songRepository,
        private readonly InputValidator $validator,
        private readonly SongFactory $songFactory
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

    public function fetchAllSongs(): \Generator
    {
        foreach ($this->songRepository->findAll() as $song) {
            yield $song;
        }
    }

    /**
     * @throws ValidationException
     * @throws FailedToSaveSongException
     */
    public function createSong(string $title, string $releaseYear, int $authorId): void
    {
        $this->validator->validateTitle($title);
        $this->validator->validateId($authorId);
        $this->validator->validateReleaseYear($releaseYear);

        $song = $this->songFactory->create($title, $releaseYear, $authorId);
        try {
            $this->songRepository->save($song);
        } catch (\Exception $exception) {
            throw new FailedToSaveSongException("Failed to save song", previous: $exception);
        }
    }

    /**
     * @throws ValidationException
     * @throws EntityNotFoundException
     * @throws FailedToSaveSongException
     */
    public function updateSong(int $id, string $title, string $releaseYear ): void
    {
        $this->validator->validateId($id);
        $this->validator->validateTitle($title);
        $this->validator->validateReleaseYear($releaseYear);

        $song = $this->songRepository->findById($id);

        if ($song === null) {
            throw new EntityNotFoundException("Song with ID {$id} not found.");
        }

        $song->changeTitle($title);
        $song->changeReleaseYear($releaseYear);

        try {
            $this->songRepository->save($song);
        } catch (\Throwable $e) {
            throw new FailedToSaveSongException("Failed to update song with ID {$id}.", 0, $e);
        }
    }
}