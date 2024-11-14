<?php

namespace App\Repository;

use App\Entity\CocoTicketPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CocoTicketPosition>
 */
class CocoTicketPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CocoTicketPosition::class);
    }
}
