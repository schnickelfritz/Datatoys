<?php

namespace App\Repository;

use App\Entity\Gridpool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gridpool>
 */
class GridpoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridpool::class);
    }

    /**
     * @return Gridpool[]
     */
    public function allPoolsFiltered(): array
    {
        return $this->findAll();
    }
}
