<?php

namespace App\Application\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Application\Exception\ValidationException;

class InputValidator
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

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
                $messages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
            }

            throw new ValidationException(implode(', ', $messages));
        }
    }
}