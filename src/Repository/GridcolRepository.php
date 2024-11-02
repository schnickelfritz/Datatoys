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

    /**
     * @return array<int, Gridcol>
     */
    public function allColumnsFiltered(): array
    {
        $columns = $this->createQueryBuilder('c')
            ->leftJoin('c.gridscopeCols', 'scopes')
            ->addSelect('scopes')
            ->getQuery()
            ->getResult()
        ;

        return is_array($columns) ? $columns : [];
    }

    /**
     * @return array<int, string>
     */
    public function allNames(): array
    {
        $names = [];
        $allCols = $this->findAll();
        foreach ($allCols as $col) {
            $names[] = $col->getName();
        }

        return $names;
    }
}
