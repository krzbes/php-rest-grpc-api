<?php

namespace App\Interfaces\Doctrine\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Song
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    private int $id;
    #[Column(type: 'string')]
    private string $title;

    #[Column(type: 'string')]
    private string $release_year;
    private int $author_id;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getReleaseYear(): string
    {
        return $this->release_year;
    }

    public function setReleaseYear(string $release_year): void
    {
        $this->release_year = $release_year;
    }
}