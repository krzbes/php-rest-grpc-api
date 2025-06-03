<?php

namespace App\Infrastructure\Doctrine\Mapper;

use App\Domain\Security\Model\AuthenticatedUser as DomainAuthUser;
use App\Infrastructure\Doctrine\Model\AuthUser as DoctrineAuthUser;

class AuthUserMapper
{
    public function toEntity(DomainAuthUser $authUser): DoctrineAuthUser
    {
        return new DoctrineAuthUser();
    }

    public function fromEntity(DoctrineAuthUser $authUser): DomainAuthUser
    {
        return new DomainAuthUser($authUser->getEmail(), $authUser->getPassword());
    }
}