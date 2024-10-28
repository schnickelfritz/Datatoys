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
    public function existingEntriesOfUserSince(User $user, DateTime $sinceDay): array
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

    /**
     * @return Workday[]
     */
    public function existingEntriesOfAllUsersSince(DateTime $sinceDay): array
    {
        $result = $this->createQueryBuilder('w')
            ->andWhere('w.day >= :sinceday')
            ->setParameter('sinceday', $sinceDay)
            ->orderBy('w.day', 'ASC')
            ->addOrderBy('w.user', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return !is_array($result) ? [] : $result;
    }

    public function countExistingEntriesOfUserSince(User $user, DateTime $sinceDay): int
    {
        return (int) $this->createQueryBuilder('w')
            ->select('COUNT(w.id)')
            ->andWhere('w.user = :user')
            ->andWhere('w.day >= :sinceday')
            ->setParameter('user', $user)
            ->setParameter('sinceday', $sinceDay)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
}
