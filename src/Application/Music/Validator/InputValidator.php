<?php

namespace App\Application\Music\Validator;

use App\Application\Music\Exception\ValidationException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InputValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @throws ValidationException
     */
    public function validateId(int $id): void
    {
        $violations = $this->validator->validate($id, [
            new Assert\NotBlank(),
            new Assert\Type('integer'),
            new Assert\Positive(),
        ]);

        if (count($violations) > 0) {
            $messages = [];

            foreach ($violations as $violation) {
                $messages[] = 'id : ' . $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $messages));
        }
    }

    public function validateTitle(string $title): void
    {
        $violations = $this->validator->validate($title, [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(['min' => 1]),
        ]);

        if (count($violations) > 0) {
            $messages = [];

            foreach ($violations as $violation) {
                $messages[] = 'title : ' . $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $messages));
        }
    }

    public function validateReleaseYear(string $releaseYear): void
    {
        $violations = $this->validator->validate($releaseYear, [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(['min' => 4, 'max' => 4]),
        ]);

        if (count($violations) > 0) {
            $messages = [];

            foreach ($violations as $violation) {
                $messages[] =  'releaseYear : ' . $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $messages));
        }
    }

    public function validateName(string $text): void
    {
        $violations = $this->validator->validate($text, [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(['min' => 1]),
        ]);

        if (count($violations) > 0) {
            $messages = [];

            foreach ($violations as $violation) {
                $messages[] = 'name : ' . $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $messages));
        }
    }

    public function validateSurname(string $text): void
    {
        $violations = $this->validator->validate($text, [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(['min' => 1]),
        ]);

        if (count($violations) > 0) {
            $messages = [];

            foreach ($violations as $violation) {
                $messages[] = 'surname : ' . $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $messages));
        }
    }
}