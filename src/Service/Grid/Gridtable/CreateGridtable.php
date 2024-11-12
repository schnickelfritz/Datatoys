<?php

declare(strict_types=1);

namespace App\Service\Grid\Gridtable;

use App\Entity\Gridtable;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\Clock\now;

final readonly class CreateGridtable
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(Gridtable $table): void
    {
        $table->setCreatedAt(now());
        $this->entityManager->persist($table);
        $this->entityManager->flush();
    }
}
