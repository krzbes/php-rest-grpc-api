<?php

namespace App\Domain\Music\Factory;

use App\Domain\Music\Model\Author;

class AuthorFactory
{
    public function create(string $name, string $surname): Author
    {
        return new Author(id: null, name: $name, surname: $surname, songs: []);
    }
}