<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setName('Martin N');
        $user1->setEmail('martin@test.de');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'tada'));
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user1);

        $user2 = new User();
        $user2->setName('Daniela S');
        $user2->setEmail('daniela@test.de');
        $user2->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user2->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user2);

        $manager->flush();
    }
}
