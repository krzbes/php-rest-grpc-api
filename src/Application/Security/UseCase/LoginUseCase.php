<?php

namespace App\Application\Security\UseCase;

use App\Application\Security\Service\PasswordVerifierInterface;
use App\Domain\Security\Event\LoginFailedException;
use App\Domain\Security\Model\AuthenticatedUser;
use App\Domain\Security\Repository\AuthenticatedUserRepository;

class LoginUseCase
{

    public function __construct(
        private readonly PasswordVerifierInterface $passwordVerifier,
        private readonly AuthenticatedUserRepository $userRepository
    ) {
    }

    /**
     * @throws LoginFailedException
     */
    public function handle(string $username, string $password): AuthenticatedUser
    {


        $user = $this->userRepository->getByEmail($username);

        if ($user === null) {
            throw new LoginFailedException();
        }

        if (!$this->passwordVerifier->isPasswordValid($user, $password)) {
            throw new LoginFailedException();
        }
        return $user;
    }
}