<?php

namespace App\Infrastructure\Doctrine\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table(name: 'song')]
class Song
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    private int $id;
    #[Column(type: 'string')]
    private string $title;

    #[Column(name: 'release_year', type: 'string', length: 4, nullable: true)]
    private ?string $releaseYear = null;
    #[ManyToOne(targetEntity: Author::class, inversedBy: 'songs')]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private ?Author $author;


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

    public function getReleaseYear(): ?string
    {
        return $this->releaseYear;
    }

    public function setReleaseYear(string $releaseYear): void
    {
        $this->releaseYear = $releaseYear;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): void
    {
        $this->author = $author;
    }
}