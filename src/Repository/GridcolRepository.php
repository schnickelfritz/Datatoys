<?php

namespace App\Repository;

use App\Entity\Gridcol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gridcol>
 */
class GridcolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridcol::class);
    }

    public function allColumnsFiltered(): array
    {
        return $this->findAll();
    }
}
