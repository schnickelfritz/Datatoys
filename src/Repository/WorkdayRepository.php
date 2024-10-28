<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Workday;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workday>
 */
class WorkdayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workday::class);
    }

    /**
     * @return Workday[]
     */
    public function existingEntriesSince(User $user, DateTime $sinceDay): array
    {
        $result = $this->createQueryBuilder('w')
            ->andWhere('w.user = :user')
            ->andWhere('w.day >= :firstday')
            ->setParameter('user', $user)
            ->setParameter('firstday', $sinceDay)
            ->getQuery()
            ->getResult()
        ;

        return !is_array($result) ? [] : $result;
    }

}
