<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserCandidate;
use App\Repository\UserRepository;

final readonly class CheckUserCandidate
{

    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function isCredentialsAvailable(UserCandidate $userCandidate): bool|string
    {
        $userByName = $this->userByName($userCandidate->getName());
        $userByEmail = $this->userByEmail($userCandidate->getEmail());
        if ($userByName !== null && $userByEmail !== null) {
            return 'user and email taken';
        }
        if ($userByName !== null) {
            return 'name taken';
        }
        if ($userByEmail !== null) {
            return 'email taken';
        }

        return true;
    }

    private function userByEmail(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email'=>$email]);
    }

    private function userByName(string $name): ?User
    {
        return $this->userRepository->findOneBy(['name'=>$name]);
    }
}