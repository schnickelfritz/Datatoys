<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Entity\GridscopeCol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function is_array;

/**
 * @extends ServiceEntityRepository<GridscopeCol>
 */
class GridscopeColRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GridscopeCol::class);
    }

    /**
     * @return array<int, Gridcol>
     */
    public function allColsInScope(Gridscope $scope): array
    {
        $scopeCols = $this->createQueryBuilder('c')
            ->andWhere('c.scope = :scope')
            ->setParameter('scope', $scope)
            ->getQuery()
            ->getResult()
        ;

        if (!is_array($scopeCols)) {
            return [];
        }

        $cols = [];
        foreach ($scopeCols as $scopeCol) {
            if ($scopeCol instanceof GridscopeCol) {
                $col = $scopeCol->getCol();
                $cols[] = $col;
            }
        }

        return $cols;
    }

    /**
     * @param Gridcol $col
     * @return array<int, Gridscope>
     */
    public function scopesByCol(Gridcol $col): array
    {
        $scopeCols = $this->createQueryBuilder('c')
            ->andWhere('c.col = :col')
            ->setParameter('col', $col)
            ->getQuery()
            ->getResult()
        ;

        if (!is_array($scopeCols)) {
            return [];
        }

        $scopes = [];
        foreach ($scopeCols as $scopeCol) {
            if ($scopeCol instanceof GridscopeCol) {
                $scope = $scopeCol->getScope();
                $scopes[] = $scope;
            }
        }

        return $scopes;

    }
}
