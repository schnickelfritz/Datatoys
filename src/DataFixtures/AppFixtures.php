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
        $user1->setName('Martin Neumann');
        $user1->setEmail('martin.neumann@hmmh.de');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'tada'));
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user1);

        $manager->flush();
    }
}
