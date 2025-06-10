<?php

namespace App\Application\Music\Service;

use App\Application\Music\Exception\EntityNotFoundException;
use App\Application\Music\Exception\FailedToSaveAuthorException;
use App\Application\Music\Exception\ValidationException;
use App\Application\Music\Validator\InputValidator;
use App\Domain\Music\Factory\AuthorFactory;
use App\Domain\Music\Model\Author;
use App\Domain\Music\Repository\AuthorRepository;
use App\Infrastructure\EventDispatcher\EventDispatcher;

class AuthorService
{
    public function __construct(
        private readonly AuthorRepository $repository,
        private readonly InputValidator $validator,
        private readonly AuthorFactory $authorFactory,
        private readonly EventDispatcher $eventDispatcher
    ) {
    }

    /**
     * @throws ValidationException
     * @throws EntityNotFoundException
     */
    public function fetchAuthor(int $id): Author
    {
        $this->validator->validateId($id);

        $author = $this->repository->findById($id);
        if ($author === null) {
            throw new EntityNotFoundException('Author not found.');
        }
        return $author;
    }

    public function fetchAllAuthors(): \Generator
    {
        foreach ($this->repository->findAll() as $author) {
            yield $author;
        }
    }

    /**
     * @throws ValidationException
     * @throws FailedToSaveAuthorException
     */
    public function createAuthor(?string $name, ?string $surname): void
    {
        $this->validator->validateName($name);
        $this->validator->validateSurname($surname);

        $author = $this->authorFactory->create($name, $surname);

        try {
            $this->repository->save($author);
        } catch (\Exception $exception) {
            throw new FailedToSaveAuthorException("Failed to save author", previous: $exception);
        }
    }

    /**
     * @throws ValidationException
     * @throws FailedToSaveAuthorException
     * @throws EntityNotFoundException
     */
    public function updateAuthor(?int $id, ?string $name, ?string $surname): void
    {
        $this->validator->validateId($id);
        $this->validator->validateName($name);
        $this->validator->validateSurname($surname);

        $author = $this->repository->findById($id);
        if ($author === null) {
            throw new EntityNotFoundException('Author not found.');
        }

        $author->changeName($name);
        $author->changeSurname($surname);

        try {
            $this->repository->save($author);
        } catch (\Throwable $e) {
            throw new FailedToSaveAuthorException("Failed to update author with ID {$id}.", 0, $e);
        }
    }

    /**
     * @throws ValidationException
     * @throws EntityNotFoundException
     */
    public function deleteAuthor(int $id): void
    {
        $this->validator->validateId($id);

        $author = $this->repository->findById($id);
        if ($author === null) {
            throw new EntityNotFoundException("Author with ID {$id} not found.");
        }

        $author->delete();

        $eventList = $author->pullEvents();
        foreach ($eventList as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}