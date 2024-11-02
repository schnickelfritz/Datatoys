<?php

namespace App\Repository;

use App\Entity\Gridcol;
use App\Entity\Gridscope;
use App\Entity\GridscopeCol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
     * @param Gridscope $scope
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
                $cols[] = $scopeCol->getCol();
            }
        }

        return $cols;
    }
}
