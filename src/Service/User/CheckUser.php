<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserCandidate;
use App\Repository\UserCandidateRepository;

final readonly class CheckUser
{
    public function __construct(
        private UserCandidateRepository $userCandidateRepository,
    ) {
    }

    public function isCredentialsAvailable(User $user): bool|string
    {
        $userCandidateByName = $this->userCandidateByName($user->getName());
        $userCandidateByEmail = $this->userCandidateByEmail($user->getEmail());
        if ($userCandidateByName !== null && $userCandidateByEmail !== null) {
            return 'user_email';
        }
        if ($userCandidateByName !== null) {
            return 'name';
        }
        if ($userCandidateByEmail !== null) {
            return 'email';
        }

        return true;
    }

    private function userCandidateByEmail(string $email): ?UserCandidate
    {
        return $this->userCandidateRepository->findOneBy(['email' => $email]);
    }

    private function userCandidateByName(string $name): ?UserCandidate
    {
        return $this->userCandidateRepository->findOneBy(['name' => $name]);
    }
}
