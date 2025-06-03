<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Security\Model\AuthenticatedUser as DomainAuthUser;
use App\Domain\Security\Repository\AuthenticatedUserRepository as DomainAuthUserRepository;
use App\Infrastructure\Doctrine\Mapper\AuthUserMapper;
use App\Infrastructure\Doctrine\Model\AuthUser as DoctrineAuthUser;
use Doctrine\ORM\EntityManagerInterface;

class AuthenticatedUserRepository implements DomainAuthUserRepository
{

    public function __construct(private readonly EntityManagerInterface $em, private readonly AuthUserMapper $mapper)
    {
    }

    public function getByEmail(string $email): ?DomainAuthUser
    {
        $user = $this->em->getRepository(DoctrineAuthUser::class)->findOneBy(['email' => $email]);

        if (null === $user) {
            return null;
        }

        return $this->mapper->fromEntity($user);
    }
}