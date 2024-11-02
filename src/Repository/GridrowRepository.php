<?php

namespace App\Repository;

use App\Entity\Gridrow;
use App\Entity\Gridtable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gridrow>
 */
class GridrowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridrow::class);
    }

    /**
     * @param Gridtable $table
     * @return array<int, Gridrow>
     */
    public function allByTable(Gridtable $table): array
    {
        $rows = $this->createQueryBuilder('r')
            ->andWhere('r.gridtable = :table')
            ->setParameter('table', $table)
            ->getQuery()
            ->getResult()
        ;

        return is_array($rows) ? $rows : [];
    }
}
