<?php

namespace App\Application\Music\Service;

use App\Application\Music\Exception\EntityNotFoundException;
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
}