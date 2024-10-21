<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserCandidate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Symfony\Component\Clock\now;

final readonly class CreateUser
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function create(UserCandidate $userCandidate, string $password): void
    {
        $user = new User();
        $user
            ->setName($userCandidate->getName())
            ->setEmail($userCandidate->getEmail())
            ->setRoles($userCandidate->getRoles())
            ->setPassword($this->userPasswordHasher->hashPassword($user, $password))
            ->setCreatedAt(now())
        ;
        $this->entityManager->persist($user);
        $this->entityManager->remove($userCandidate);
        $this->entityManager->flush();
    }

}