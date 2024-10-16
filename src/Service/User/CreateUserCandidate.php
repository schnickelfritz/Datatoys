<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserCandidate;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateUserCandidate
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
    )
    {
    }

    public function create(UserCandidate $userCandidate): bool|string
    {
        if ($this->userByEmail($userCandidate->getEmail()) !== null) {
            return 'email';
        }
        if ($this->userByName($userCandidate->getName()) !== null) {
            return 'name';
        }

        $this->entityManager->persist($userCandidate);
        $this->entityManager->flush();

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