<?php

namespace App\Repository;

use App\Entity\Gridcell;
use App\Entity\Gridrow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gridcell>
 */
class GridcellRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridcell::class);
    }

    /**
     * @param array<int, Gridrow> $rows
     * @return array<int, Gridcell>
     */
    public function allByRows(array $rows): array
    {
        $cells = $this->createQueryBuilder('c')
            ->andWhere('c.gridrow IN (:rows)')
            ->setParameter('rows', $rows)
            ->getQuery()
            ->getResult()
        ;

        return is_array($cells) ? $cells : [];
    }
}
