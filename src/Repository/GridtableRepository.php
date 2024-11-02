<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gridtable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function is_array;

/**
 * @extends ServiceEntityRepository<Gridtable>
 */
class GridtableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridtable::class);
    }

    /**
     * @return Gridtable[]
     */
    public function allTablesFiltered(): array
    {
        $tables = $this->createQueryBuilder('t')
            ->leftJoin('t.gridrows', 'rows')
            ->addSelect('rows')
            ->orderBy('t.name')
            ->getQuery()
            ->getResult()
        ;

        return is_array($tables) ? $tables : [];
    }
}
