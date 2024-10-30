<?php

namespace App\Repository;

use App\Entity\Gridscope;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gridscope>
 */
class GridscopeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gridscope::class);
    }

    public function choices(): array
    {
        $choices = ['-' => null];
        foreach ($this->findAll() as $scope) {
            $choices[$scope->getName()] = $scope;
        }

        return $choices;
    }
}
