<?php

namespace App\Application\Music\Service;

use App\Application\Music\Exception\EntityNotFoundException;
use App\Application\Music\Exception\FailedToSaveSongException;
use App\Application\Music\Exception\ValidationException;
use App\Application\Music\Validator\InputValidator;
use App\Domain\Music\Factory\SongFactory;
use App\Domain\Music\Model\Song;
use App\Domain\Music\Repository\SongRepository;
use App\Infrastructure\EventDispatcher\EventDispatcher;


class SongService
{
    public function __construct(
        private readonly SongRepository $songRepository,
        private readonly InputValidator $validator,
        private readonly SongFactory $songFactory,
        private readonly EventDispatcher $eventDispatcher
    ) {
    }

    /**
     * @throws \App\Application\Music\Exception\ValidationException
     * @throws \App\Application\Music\Exception\EntityNotFoundException
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
     * @throws \App\Application\Music\Exception\FailedToSaveSongException
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
     * @throws \App\Application\Music\Exception\EntityNotFoundException
     * @throws \App\Application\Music\Exception\FailedToSaveSongException
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

    /**
     * @throws ValidationException
     * @throws \App\Application\Music\Exception\EntityNotFoundException
     */
    public function deleteSong(int $id): void{
        $this->validator->validateId($id);
        $song = $this->songRepository->findById($id);
        if ($song === null) {
            throw new EntityNotFoundException("Song with ID {$id} not found.");
        }
        $song->delete();

        $eventList = $song->pullEvents();
        foreach ($eventList as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}