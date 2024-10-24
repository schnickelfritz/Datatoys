<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserCandidate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;
use function Symfony\Component\Clock\now;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $candidate1 = new UserCandidate();
        $candidate1
            ->setName('Bernd B')
            ->setEmail('bernd@test.de')
            ->setRoles(['ROLE_WORKTIME_PLANNER'])
            ->setCreatedAt(now());
        $manager->persist($candidate1);

        $user1 = new User();
        $user1->setName('Martin N');
        $user1->setEmail('martin@test.de');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'tada'));
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setCreatedAt(now());
        $manager->persist($user1);

        $user2 = new User();
        $user2->setName('Daniela S');
        $user2->setEmail('daniela@test.de');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'tada1'));
        $user2->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user2->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user2);

        $user3 = new User();
        $user3->setName('Jochen S');
        $user3->setEmail('jochen@test.de');
        $user3->setPassword($this->passwordHasher->hashPassword($user3, 'tada2'));
        $user3->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user3);

        $user4 = new User();
        $user4->setName('Thmoas A');
        $user4->setEmail('thomas@test.de');
        $user4->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user4->setPassword($this->passwordHasher->hashPassword($user4, 'tada3'));
        $user4->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user4);

        $user5 = new User();
        $user5->setName('Lilly L');
        $user5->setEmail('lilly@test.de');
        $user5->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user5->setPassword($this->passwordHasher->hashPassword($user5, 'tada4'));
        $user5->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user5);

        $user6 = new User();
        $user6->setName('Maike A');
        $user6->setEmail('maike@test.de');
        $user6->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user6->setPassword($this->passwordHasher->hashPassword($user6, 'tada5'));
        $user6->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user6);

        $user7 = new User();
        $user7->setName('Thorsten T');
        $user7->setEmail('thorsten@test.de');
        $user7->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user7->setPassword($this->passwordHasher->hashPassword($user7, 'tada6'));
        $user7->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user7);

        $user8 = new User();
        $user8->setName('Silvia S');
        $user8->setEmail('silvia@test.de');
        $user8->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user8->setPassword($this->passwordHasher->hashPassword($user8, 'tada7'));
        $user8->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user8);

        $user9 = new User();
        $user9->setName('Michael H');
        $user9->setEmail('michael@test.de');
        $user9->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user9->setPassword($this->passwordHasher->hashPassword($user9, 'tada8'));
        $user9->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user9);

        $user10 = new User();
        $user10->setName('Birgit D');
        $user10->setEmail('birgit@test.de');
        $user10->setRoles(['ROLE_WORKTIME_PLANNER']);
        $user10->setPassword($this->passwordHasher->hashPassword($user10, 'tada9'));
        $user10->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user10);

        $manager->flush();

    }
}
