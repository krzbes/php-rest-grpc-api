<?php

namespace App\Infrastructure\Doctrine\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table(name: 'user')]
class AuthUser
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    private int $id;
    #[Column(type: 'string', length: 180)]
    private string $email;

    #[Column(type: 'string')]
    private string $password;
    #[Column(type: 'json')]
    private array $roles = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}