<?php

namespace App\Interfaces\Doctrine\Model;

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
    #[ManyToOne(targetEntity: Author::class)]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private ?Author $author;


    public function __construct(int $id, string $title, ?string $release_year, ?Author $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->releaseYear = $release_year;
        $this->author = $author;
    }

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
        return $this->releaseYear;
    }

    public function setReleaseYear(string $release_year): void
    {
        $this->releaseYear = $release_year;
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