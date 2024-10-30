<?php

namespace App\Repository;

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
}
