<?php

namespace App\Service\Grid;

use App\Entity\Gridpool;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\Clock\now;

final readonly class CreateGridpool
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function create(Gridpool $pool): void
    {
        $pool->setCreatedAt(now());
        $this->entityManager->persist($pool);
        $this->entityManager->flush();
    }
}