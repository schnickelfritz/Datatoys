<?php

namespace App\Repository;

use App\Entity\Gridscope;
use App\Entity\Gridsetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gridsetting>
 */
class GridsettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridsetting::class);
    }

    /**
     * @param Gridscope $scope
     * @return Gridsetting[]
     */
    public function allByScope(Gridscope $scope): array
    {
        $settings = $this->createQueryBuilder('s')
            ->andWhere('s.scope = :scope')
            ->setParameter('scope', $scope)
            ->getQuery()
            ->getResult()
        ;

        return is_array($settings) ? $settings : [];
    }
}
